<?php
	function buscaRegistro($tabla,$predicado="",$camposRetorno=""){
		$db = \Config\Database::connect();
		if(!$camposRetorno){
			$camposRetorno="*";
		} 
         $sql = "select  $camposRetorno  from $tabla $predicado";   
		//return $sql."<br>";
        // $miscon = mysql_query($sql);
        //  while ( $row = mysql_fetch_array($miscon,MYSQL_ASSOC) ) {                     
        //      $rower[] = $row;
        // }
        // mysql_free_result($miscon);
        return $db->query($sql)->getResult();
    }
	function fq_tiene_parametro($id_muestra, $id_parametro){
        $tabla = "ensayo_vs_muestra em inner join ensayo en on em.id_ensayo=en.id_ensayo";
        $predicado ='where em.id_muestra='.$id_muestra.' and en.id_parametro = '.$id_parametro;
        $campo_retorno = ('em.id_ensayo_vs_muestra');
                        
        return buscaRegistro($tabla, $predicado, $campo_retorno);
   	}
   	function disable_frm($valor, $user_rol_id){
       	if($valor){
            if( $user_rol_id ==1 || $user_rol_id ==2 || $user_rol_id ==3){
                //1 super admin
                //2 gerencia
                //3 director tecnico
                $disable ='';
            }else{        
                $disable ='disabled="true"';
            }
        }else{
            $disable ='';
        }
        return $disable;
   	}
   	function cambiar_campos_resultados_fq_directo($id_ensayo_vs_muestra, $valor, $id_parametro, $nombre_campo_frm, $nombre_campo_bd){
	    //1. evalue si el campo es nuemerico (solo se permite numeros)
	    //2. si es numero
	    //3. es posible que ya este creado. preguntamos si ya esta creado el registro en fq
	    //4. si esta creado se actualiza
	    //5. no creado se inserta
	    //6. se pregunta si ya estan todos los valores para emitir resultado
	    
	    if (is_numeric (str_replace(",","",$valor))) {
	        //guarda resultado para certificado
	        almacena_primer_campo($id_ensayo_vs_muestra, $valor);
	        almacena_campo_resultado($id_ensayo_vs_muestra, $valor);
	        
	        //almacena_campo_fq($id_ensayo_vs_muestra, $valor, $id_parametro, $nombre_campo_frm, $nombre_campo_bd);
	        //$mensaje_resultado = comprueba_calcular_resultado($id_ensayo_vs_muestra,  $id_parametro);
	        
	        $respuesta->assign($nombre_campo_frm,"style", "border: 2px solid green;");  
	        $respuesta->assign($nombre_campo_frm,"disabled","true");
	        $respuesta->assign($nombre_campo_frm,"onblur","");
	        
	        $mensaje .="Ok";
	    }else{
	        $respuesta->assign($nombre_campo_frm,"style", "border: 2px solid  red;");            
	        $mensaje ="Valor no permitido";
	    }         
	    $respuesta->assign("campo_repuesta_%_".$id_ensayo_vs_muestra, "innerHTML", $mensaje_resultado);
	    $respuesta->assign($campo_salida, "innerHTML", $mensaje);
	    return $respuesta;
	}
   	function almacena_campo_fq($id_ensayo_vs_muestra, $valor, $id_parametro, $nombre_campo_frm, $nombre_campo_bd){
	    $result_humedad = procesar_registro_fetch('ensa_mues_fq', 'id_ensayo_vs_muestra', $id_ensayo_vs_muestra, 'id_parametro', $id_parametro);
	    if($result_humedad[0]->id_ensayo_vs_muestra){
	        $texto = "update ensa_mues_fq set
	                    $nombre_campo_bd = '$valor'
	                    
	                    where id_ensayo_vs_muestra = $id_ensayo_vs_muestra";
	                    //, id_user= ".$_SESSION['user_id']."  se activara si se requiere almacenar el ultimo que lo modifico
	    }else{
	        $texto = "insert into ensa_mues_fq (id_ensayo_vs_muestra, id_parametro, $nombre_campo_bd, id_user)
	                   VALUES
	                   ($id_ensayo_vs_muestra, $id_parametro, '".$valor."', ".session('user')->id." )";
	    }
	    
	    $db = \Config\Database::connect();
	    $query = $db->query($texto);    
	   
	    $nombre_campo_frm = str_replace("frm_","",$nombre_campo_frm);
	    
	    //almacena_auditoria($id_ensayo_vs_muestra, $valor,$nombre_campo_frm);
	      
	    return $texto;  
	}
	function comprueba_calcular_resultado($id_ensayo_vs_muestra, $id_parametro){
		$result_fq = procesar_registro_fetch('ensa_mues_fq', 'id_ensayo_vs_muestra', $id_ensayo_vs_muestra, 'id_parametro', $id_parametro);
    	$result_fq[0]->result_1 = str_replace(",",".",$result_fq[0]->result_1);
	       // return 'Algo';
    	$result_fq[0]->result_2 = str_replace(",",".",$result_fq[0]->result_2);
    	$result_fq[0]->result_3 = str_replace(",",".",$result_fq[0]->result_3); 
    	$result_fq[0]->result_4 = str_replace(",",".",$result_fq[0]->result_4);

    	$nombre_campo_frm ="";
    	if(isset($result_fq[0]->result_1) && isset($result_fq[0]->result_2) && isset($result_fq[0]->result_3)  && $result_fq[0]->id_equipo && $id_parametro ==24){
	        $salida="Calcula el dato humedad";
	        $result =   ( ( ($result_fq[0]->result_1 + $result_fq[0]->result_2 ) - $result_fq[0]->result_3 ) / $result_fq[0]->result_2  ) * 100;
	        $nombre_campo_frm ="resultado_humedad";
		}elseif(isset($result_fq[0]->result_1) && isset($result_fq[0]->result_2) && isset($result_fq[0]->result_3)  && isset($result_fq[0]->result_4) && $result_fq[0]->id_equipo && $result_fq[0]->id_factor && $id_parametro ==25){
	        $salida="Calcula el dato proteina";
	        $fila_factor = procesar_registro_fetch ('factor_fq', 'id_factor', $result_fq[0]->id_factor);
	        $fila_factor[0]->valor = str_replace(",",".",$fila_factor[0]->valor);
	          
	        $result = (( ( $result_fq[0]->result_3 - $result_fq[0]->result_2 )* 14 * $result_fq[0]->result_4 * $fila_factor[0]->valor )   /  ($result_fq[0]->result_1) )  *  0.1;
	        $nombre_campo_frm ="resultado_proteina";
	    }elseif(isset($result_fq[0]->result_1) && isset($result_fq[0]->result_2) && isset($result_fq[0]->result_3)  && $result_fq[0]->id_equipo && $id_parametro ==26){
	        $salida="Calcula el dato fibra";
	        $result = ( ($result_fq[0]->result_2 - $result_fq[0]->result_1)/ $result_fq[0]->result_3 ) * 100;
	        $nombre_campo_frm ="resultado_fibra";
	    }elseif(isset($result_fq[0]->result_1) && isset($result_fq[0]->result_2) && isset($result_fq[0]->result_3)  && $result_fq[0]->id_equipo && $id_parametro ==27){
       		$salida="Calcula el dato grasa";
        	$result = ( ($result_fq[0]->result_3 - $result_fq[0]->result_1)/ $result_fq[0]->result_2 ) * 100;
        	$nombre_campo_frm ="resultado_grasa";
    	}elseif(isset($result_fq[0]->result_1) && isset($result_fq[0]->result_2) && isset($result_fq[0]->result_3)  && $result_fq[0]->id_equipo &&  ($id_parametro ==131 || $id_parametro ==188 ) ){
	        $salida="Calcula el dato grasa GERBER 1"; 
	        $result = ( ($result_fq[0]->result_3 - $result_fq[0]->result_1)/ $result_fq[0]->result_2 ) * 100;
	        $nombre_campo_frm ="resultado_grasa_gerber_1";
	    }elseif(isset($result_fq[0]->result_1) && isset($result_fq[0]->result_2) && isset($result_fq[0]->result_3)  && $result_fq[0]->id_equipo && $id_parametro == 29){
	        $salida="Calcula el dato ceniza";
	        $result = ( ($result_fq[0]->result_3 - $result_fq[0]->result_1)/ $result_fq[0]->result_2 ) * 100;
	        $nombre_campo_frm ="resultado_ceniza";
	    }elseif(isset($result_fq[0]->result_1) && isset($result_fq[0]->result_2) && isset($result_fq[0]->result_3)  && $result_fq[0]->id_factor && $result_fq[0]->id_equipo && $id_parametro ==151){
	        $salida="Calcula el dato acidez";
	        $fila_factor = procesar_registro_fetch ('factor_fq', 'id_factor', $result_fq[0]->id_factor);
	        $fila_factor[0]->valor = str_replace(",",".",$fila_factor[0]->valor);
	        $result =  ($result_fq[0]->result_2 *  $result_fq[0]->result_3 * $fila_factor[0]->valor * 100)/ $result_fq[0]->result_1 ;
	        $nombre_campo_frm ="resultado_acidez";
	    }elseif(isset($result_fq[0]->result_1) && isset($result_fq[0]->result_2) && isset($result_fq[0]->result_3)  && $result_fq[0]->id_factor && $result_fq[0]->id_equipo && $id_parametro ==157){
	        $salida="Calcula el dato acidez";
	        $fila_factor = procesar_registro_fetch ('factor_fq', 'id_factor', $result_fq[0]->id_factor);
	        $fila_factor[0]->valor = str_replace(",",".",$fila_factor[0]->valor);
	        $result =  ($result_fq[0]->result_2 *  $result_fq[0]->result_3 * $fila_factor[0]->valor * 100)/ $result_fq[0]->result_1 ;
	        $nombre_campo_frm ="resultado_acidez";
	    }elseif(isset($result_fq[0]->result_1) && isset($result_fq[0]->result_2) && isset($result_fq[0]->result_3)  && $result_fq[0]->id_factor && $result_fq[0]->id_equipo && $id_parametro == 120){
	        $salida="Calcula el dato Bases volatiles Nitrogenadas.";
	        $fila_factor = procesar_registro_fetch ('factor_fq', 'id_factor', $result_fq[0]->id_factor);
	        $fila_factor[0]->valor = str_replace(",",".",$fila_factor[0]->valor);
	        $result =  ($result_fq[0]->result_2 *  $result_fq[0]->result_3 * $fila_factor[0]->valor * 100)/ $result_fq[0]->result_1 ;
	        $nombre_campo_frm ="resultado_base_v";
	    }elseif(isset($result_fq[0]->result_1) && isset($result_fq[0]->result_2) && isset($result_fq[0]->result_3)  && $result_fq[0]->id_factor && $result_fq[0]->id_equipo && $id_parametro == 111){
	        $salida="Calcula el dato Bases volatiles Nitrogenadas.";
	        $fila_factor = procesar_registro_fetch ('factor_fq', 'id_factor', $result_fq[0]->id_factor);
	        $fila_factor[0]->valor = str_replace(",",".",$fila_factor[0]->valor);
	        $result =  ($result_fq[0]->result_2 *  $result_fq[0]->result_3 * $fila_factor[0]->valor * 100)/ $result_fq[0]->result_1 ;
	        $nombre_campo_frm ="resultado_base_v";
	    }else{
	        $salida ="pendiente de datos";
	    }

	    if($nombre_campo_frm){
	        $result =   round($result, 2);
	        //pasamos el resultado de punto a coma
	        $result = str_replace(".",",",$result);
	        $salida = $result  ." %";
	        //guarda campo en result_fq
	        almacena_campo_fq($id_ensayo_vs_muestra, $result, $id_parametro, $nombre_campo_frm, 'result_5');
	        //guarda resultado para certificado
	        almacena_primer_campo($id_ensayo_vs_muestra, $result);
	        almacena_campo_resultado($id_ensayo_vs_muestra, $result);
	    }
	        
	    return $salida;
	}
	function calcula_carbohidratos($id_ensayo_vs_muestra, $redondeo){
        $result_e_v_m = procesar_registro_fetch('ensayo_vs_muestra', 'id_ensayo_vs_muestra', $id_ensayo_vs_muestra);
        $fila_carbo = fq_tiene_parametro($result_e_v_m[0]->id_muestra, 28);
        if (isset($fila_carbo[0]->id_ensayo_vs_muestra)){
            $salida="Si tiene CoH 3";
            $aux_todos_llenos ="Si";
            $aux_total=0;
            //1. si tiene humedad y esta calculado
            $fila_humedad = fq_tiene_parametro($result_e_v_m[0]->id_muestra, 24);
            if (isset($fila_humedad[0]->id_ensayo_vs_muestra)){
                $salida.="<br>Si tiene HUMEDAD ";
                $result_humedad = procesar_registro_fetch('ensa_mues_fq', 'id_ensayo_vs_muestra', $fila_humedad[0]->id_ensayo_vs_muestra);
                if(isset($result_humedad[0]->result_5)){
                        $salida.="".  $result_humedad[0]->result_5;
                        $aux_total = $aux_total + str_replace(",",".",$result_humedad[0]->result_5);
                }else{
                    $salida.='Sin resultado';
                    $aux_todos_llenos ="No";
                }
            }else{
                $salida.="No tiene HUMEDAD";
            }
            
            //2. si tiene grasa y esta calculado 27
            $fila_grasa = fq_tiene_parametro($result_e_v_m[0]->id_muestra, 27);
            if (isset($fila_grasa[0]->id_ensayo_vs_muestra)){
                $salida.="<br>Si tiene GRASA ";
                $result_grasa = procesar_registro_fetch('ensa_mues_fq', 'id_ensayo_vs_muestra', $fila_grasa[0]->id_ensayo_vs_muestra);
                if(isset($result_grasa[0]->result_5)){
                        $salida.="".  $result_grasa[0]->result_5;
                        $aux_total = $aux_total + str_replace(",",".",$result_grasa[0]->result_5);
                }else{
                    $salida.='Sin resultado';
                    //$aux_todos_llenos ="No";
                }
            }else{
                $salida.="<br>No tiene GRASA";
            }
            //2.1 si tiene grasa gerber y esta calculado 131 
            $fila_grasa_g_1 = fq_tiene_parametro($result_e_v_m[0]->id_muestra, 131);
            if (isset($fila_grasa_g_1[0]->id_ensayo_vs_muestra)){
                $salida.="<br>Si tiene GRASA GERBER ";
                $result_grasa_g_1 = procesar_registro_fetch('ensa_mues_fq', 'id_ensayo_vs_muestra', $fila_grasa_g_1[0]->id_ensayo_vs_muestra);
                if(isset($result_grasa_g_1[0]->result_5)){
                        $salida.="".  $result_grasa_g_1[0]->result_5;
                        $aux_total = $aux_total + str_replace(",",".",$result_grasa_g_1[0]->result_5);
                }else{
                    $salida.='Sin resultado';
                   // $aux_todos_llenos ="No";
                }
            }else{
                $salida.="<br>No tiene GRASA  GERBER";
            }
            
            //2.2 si tiene grasa gerber 2 y esta calculado 188 
            $fila_grasa_g_1 = fq_tiene_parametro($result_e_v_m[0]->id_muestra, 188);
            if (isset($fila_grasa_g_1[0]->id_ensayo_vs_muestra)){
                $salida.="<br>Si tiene GRASA GERBER ";
                $result_grasa_g_1 = procesar_registro_fetch('ensa_mues_fq', 'id_ensayo_vs_muestra', $fila_grasa_g_1[0]->id_ensayo_vs_muestra);
                if(isset($result_grasa_g_1[0]->result_5)){
                        $salida.="".  $result_grasa_g_1[0]->result_5;
                        $aux_total = $aux_total + str_replace(",",".",$result_grasa_g_1[0]->result_5);
                }else{
                    $salida.='Sin resultado';
                    //$aux_todos_llenos ="No";
                }
            }else{
                $salida.="<br>No tiene GRASA";
            }
            
            //3. si tiene fibra y esta calculado 26
            $fila_fibra = fq_tiene_parametro($result_e_v_m[0]->id_muestra, 26);
            if (isset($fila_fibra[0]->id_ensayo_vs_muestra)){
                $salida.="<br>Si tiene FIBRA ";
                $result_fibra = procesar_registro_fetch('ensa_mues_fq', 'id_ensayo_vs_muestra', $fila_fibra[0]->id_ensayo_vs_muestra);
                if(isset($result_fibra[0]->result_5)){
                        $salida.="".  $result_fibra[0]->result_5;
                        $aux_total = $aux_total + str_replace(",",".",$result_fibra[0]->result_5);
                }else{
                    $salida.='Sin resultado';
                    $aux_todos_llenos ="No";
                }
            }else{
                $salida.="<br>No tiene FIBRA";
            }
            // 4. si tiene ceniza y esta calculado
            $fila_ceniza = fq_tiene_parametro($result_e_v_m[0]->id_muestra, 29);
            if (isset($fila_ceniza[0]->id_ensayo_vs_muestra)){
                $salida.="<br>Si tiene CENIZA ";
                $result_ceniza = procesar_registro_fetch('ensa_mues_fq', 'id_ensayo_vs_muestra', $fila_ceniza[0]->id_ensayo_vs_muestra);
                if(isset($result_ceniza[0]->result_5)){
                        $salida.="".  $result_ceniza[0]->result_5;
                        $aux_total = $aux_total + str_replace(",",".",$result_ceniza[0]->result_5);
                }else{
                    $salida.='<br>Sin resultado';
                    $aux_todos_llenos ="No";
                }
            }else{
                $salida.="<br>No tiene CENIZA";
            }
           //5. si tiene proteina y esta calculado 25
            $fila_proteina = fq_tiene_parametro($result_e_v_m[0]->id_muestra, 25);
            if (isset($fila_proteina[0]->id_ensayo_vs_muestra)){
                $salida.="<br>Si tiene PROTEINA ";
                $result_proteina = procesar_registro_fetch('ensa_mues_fq', 'id_ensayo_vs_muestra', $fila_proteina[0]->id_ensayo_vs_muestra);
                if(isset($result_proteina[0]->result_5)){
                        $salida.="".  $result_proteina[0]->result_5;
                        $aux_total = $aux_total + str_replace(",",".",$result_proteina[0]->result_5);
                }else{
                    $salida.='<br>Sin resultado';
                    $aux_todos_llenos ="No";
                }
            }else{
                $salida.="<br>No tiene PROTEINA";
            }
            
            $salida.="<br>total: ".$aux_total;
            $salida.="<br>todos llenos: ".$aux_todos_llenos."<br>Total :  ";
            //6 si todos los que tiene estan calculados se calcula carbohidratos
            if($aux_todos_llenos=="Si"){
                $result = 100 - $aux_total;
                // ajuste decimales
                    $result = round($result, $redondeo);
                //pasamos el resultado de punto a coma
                        $result = str_replace(".",",",$result);
                        
                //guarda resultado para certificado
                almacena_primer_campo($fila_carbo[0]->id_ensayo_vs_muestra, $result);
                almacena_campo_resultado($fila_carbo[0]->id_ensayo_vs_muestra, $result);
                $salida .= $result;
            }else{
                $salida .= "<br>Sin resultados";
            }
        }else{
            $salida="No tiene CoH";
        }
        return $salida;
 	}
 	function calcula_calorias($id_ensayo_vs_muestra, $redondeo=4){
     
      //preguntamos si tiene calorias 30
        //si lo tiene buscamos que procesos tiene 
        //1. si tiene grasa y esta calculado 27
        //2. si tiene proteina y esta calculado 25
        //3. si tiene  carbohidratos y esta calculado 28
        //4 si todos los que tiene estan calculados se calcula carbohidratos
        
        //preguntamos si tiene calorias
        $result_e_v_m = procesar_registro_fetch('ensayo_vs_muestra', 'id_ensayo_vs_muestra', $id_ensayo_vs_muestra);
        $fila_calo = fq_tiene_parametro($result_e_v_m[0]->id_muestra, 30);
        if (isset($fila_calo[0]->id_ensayo_vs_muestra)){
            $salida='<br>Si tiene calorias ';
            $aux_todos_llenos ="Si";
            $aux_total=0;
            //1. si tiene grasa y esta calculado 27
            $fila_grasa = fq_tiene_parametro($result_e_v_m[0]->id_muestra, 27);
            if ($fila_grasa[0]->id_ensayo_vs_muestra){
                $salida.="<br>Si tiene GRASA ";
                $result_grasa = procesar_registro_fetch('ensa_mues_fq', 'id_ensayo_vs_muestra', $fila_grasa[0]->id_ensayo_vs_muestra);
                if(isset($result_grasa[0]->result_5)){
                        $salida.="".  $result_grasa[0]->result_5;
                        $aux_total =  str_replace(",",".",$result_grasa[0]->result_5) * 9;
                }else{
                    $salida.='Sin resultado';
                    //$aux_todos_llenos ="No";
                }
            }else{
                $salida.="<br>No tiene GRASA";
            }
             
            //2.1 si tiene grasa gerber y esta calculado 131 
            $fila_grasa_g_1 = fq_tiene_parametro($result_e_v_m[0]->id_muestra, 131);
            if (isset($fila_grasa_g_1[0]->id_ensayo_vs_muestra)){
                $salida.="<br>Si tiene GRASA GERBER ";
                $result_grasa_g_1 = procesar_registro_fetch('ensa_mues_fq', 'id_ensayo_vs_muestra', $fila_grasa_g_1[0]->id_ensayo_vs_muestra);
                if(isset($result_grasa_g_1[0]->result_5)){
                        $salida.="".  $result_grasa_g_1[0]->result_5;
                        $aux_total =  str_replace(",",".",$result_grasa_g_1[0]->result_5) * 9;
                }else{
                    $salida.='Sin resultado';
                   // $aux_todos_llenos ="No";
                }
            }else{
                $salida.="<br>No tiene GRASA  GERBER";
            }
            
            //2. si tiene proteina y esta calculado 25
            $fila_proteina = fq_tiene_parametro($result_e_v_m[0]->id_muestra, 25);
            if (isset($fila_proteina[0]->id_ensayo_vs_muestra)){
                $salida.="<br>Si tiene PROTEINA ";
                $result_proteina = procesar_registro_fetch('ensa_mues_fq', 'id_ensayo_vs_muestra', $fila_proteina[0]->id_ensayo_vs_muestra);
                if(isset($result_proteina[0]->result_5)){
                        $salida.="".  $result_proteina[0]->result_5;
                        $aux_total = $aux_total + (str_replace(",",".",$result_proteina[0]->result_5) * 4) ;
                }else{
                    $salida.='Sin resultado';
                    $aux_todos_llenos ="No";
                }
            }else{
                $salida.="<br>No tiene PROTEINA";
            }
            
           //3. tiene carbohidratos
           $fila_coh = fq_tiene_parametro($result_e_v_m[0]->id_muestra, 28);
           
           if (isset($fila_coh[0]->id_ensayo_vs_muestra)){
                $salida.="<br> Si tiene carbohidratos ";
                $result_calorias = procesar_registro_fetch('ensayo_vs_muestra', 'id_ensayo_vs_muestra', $fila_coh[0]->id_ensayo_vs_muestra);
                if(isset($result_calorias[0]->resultado_mensaje)){
                        $salida.="".  $result_calorias[0]->resultado_mensaje;
                        $aux_total = $aux_total + (str_replace(",",".",$result_calorias[0]->resultado_mensaje) * 4) ;
                }else{
                    $salida.='Sin resultado';
                    $aux_todos_llenos ="No";
                }
            }else{
                $salida.="<br>No tiene carbohidratos";
            }
            
            $salida.="<br>total".$aux_total;
            $salida.="<br>todos llenos ".$aux_todos_llenos." <br>" ;
            //6 si todos los que tiene estan calculados se calcula calorias
            if($aux_todos_llenos=="Si"){
                $result = $aux_total;
                // ajuste decimales
                    $result = round($result, $redondeo);
                //pasamos el resultado de punto a coma
                        $result = str_replace(".",",",$result);
                        
                //guarda resultado para certificado
                almacena_primer_campo($fila_calo[0]->id_ensayo_vs_muestra, $result);
                almacena_campo_resultado($fila_calo[0]->id_ensayo_vs_muestra, $result);
                $salida.= $result;
            }else{
                $salida .= "<br> Sin resultados";
            }
            
        }else{
            $salida.='No tiene calorias';
        }
        return $salida;
   	}
?>