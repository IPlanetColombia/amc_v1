<?php
	function evalua_alerta($med_valor_min ,$med_valor_max, $valor, $id_tipo_analisis, $id_ensayo_vs_muestra, $quien_invoca=1){//2 son de formaulario de consultas y no generea correo
    	$med_valor_min = formatea_valor_min_max($med_valor_min);
    	$med_valor_max = formatea_valor_min_max($med_valor_max);
    	$valor = formatea_mh_total($valor);
    	$mensaje ='';
   
    
	    if(strlen($med_valor_min)>0 || strlen($med_valor_max)>0 ){//|| $med_valor_max || $med_valor_max==0
	        $valor   = str_replace('<', '',$valor);
	        $valor   = str_replace('>', '',$valor);
	        $valor   = str_replace('.', '',$valor);
	        $valor   = str_replace(',', '.',$valor);//para que sea numerico.
	        $valor   = trim($valor);
	        
	        if(is_numeric($valor) ){
				//$med_valor_max =(trim($med_valor_max) || $med_valor_max==0)?$med_valor_max:(trim($med_valor_max))?$med_valor_max:9999999;
	            //$med_valor_max =($med_valor_max || $med_valor_max==0)?$med_valor_max:9999999;
	            //$med_valor_max =(trim($med_valor_max)==false)?$med_valor_max:9999999;
				$med_valor_max =(strlen($med_valor_max)>0)?$med_valor_max:9999999;
				
				$med_valor_min   = str_replace(',', '.',$med_valor_min);//para que sea numerico
	            $med_valor_max   = str_replace(',', '.',$med_valor_max);//para que sea numerico
	            
				
				
	            if($id_tipo_analisis == 3){// FA 
	                
	                $mensaje =($med_valor_min <= $valor  &&   $valor <= $med_valor_max  )?'-MAN-No genera Alerta 1:':'-MAS-SI genera Alerta 1';//
	                $mensaje.="min:".$med_valor_min." max:".$med_valor_max;
	                
	            }else{
	                //$med_valor_max =($med_valor_max)?$med_valor_max:9999999;
					//$med_valor_max =($med_valor_max || $med_valor_max==0)?$med_valor_max:9999999;
					//$med_valor_max =($med_valor_max)?$med_valor_max:9999999;
					//$med_valor_max =(trim($med_valor_max) || $med_valor_max==0)?$med_valor_max:(trim($med_valor_max))?$med_valor_max:9999999;
	                $mensaje = (0 <= $valor && $valor <= $med_valor_max  ) ? '-MAN-No genera Alerta 2':'-MAS-SI genera Alerta 2:';    
	                 $mensaje.="min: $med_valor_min Toma 0 x ser <>FA max: $med_valor_max - valor: $valor";
	                 // $mensaje = '0 <= '.$valor.' && '.$valor.' <= '.$med_valor_max;
	           }
	        }else{
	                
	            $valor          = strtoupper($valor);                
	            $med_valor_min  = strtoupper($med_valor_min);
	            $med_valor_max  = strtoupper($med_valor_max);
	              
	            $mensaje        = ($valor == $med_valor_min || $valor == $med_valor_max)?'-MAN-No genera Alerta 3':'-MAS-SI genera Alerta 3 ';
	             $mensaje.="min:".$med_valor_min." max:".$med_valor_max.' valor:'.$valor;
	               
	        }
	    }else{
	        $mensaje ='-MAN-No genera Alerta 4';    
	    }
	    if(preg_match("/-MAS-/", $mensaje)){
	        if($quien_invoca==1){
	            // php_envia_mail($id_ensayo_vs_muestra);
	        }
	    }
	    //echo $mensaje;
	  return $mensaje;
	}
	function formatea_valor_min_max($valor){
	    $valor          = str_replace('<', '',$valor);
	    $valor          = str_replace('>', '',$valor);
	    $valor          = str_replace('.', '',$valor);
	    $valor          = str_replace('Máximo', '',  $valor);
	    $valor          = str_replace('Mínimo', '',  $valor);    
	    $valor          = str_replace('Máx', '',  $valor);
	    $valor          = str_replace('Máx', '',$valor);
	    $valor          = str_replace('Máx.', '',$valor);
	    $valor          = str_replace('Max', '',$valor);
	    $valor          = str_replace('(*)', '',$valor);
	    $valor          = str_replace('*', '',$valor);
	    $valor          = trim($valor);
	    return $valor;
	}
	function almacena_primer_campo($id_ensayo_vs_muestra, $valor){
		$db = \Config\Database::connect();
     	$query = "update ensayo_vs_muestra set
                    resultado_analisis = '$valor',
                        id_responsable= ".session('user')->id." 
                    where id_ensayo_vs_muestra = $id_ensayo_vs_muestra";
        $data = $db->query($query);

      
      	almacena_auditoria($id_ensayo_vs_muestra, $valor,'resultado_analisis');
	}
	function almacena_segundo_campo($id_ensayo_vs_muestra, $valor){
		$db = \Config\Database::connect();
     	$texto = "update ensayo_vs_muestra set
                    resultado_analisis2 = '$valor',
                        id_responsable= ".session('user')->id." 
                    where id_ensayo_vs_muestra = $id_ensayo_vs_muestra";
      	$query = $db->query($texto);     
      	almacena_auditoria($id_ensayo_vs_muestra, $valor,'resultado_analisis2');
	}
	function almacena_campo_resultado($id_ensayo_vs_muestra, $valor){
		$db = \Config\Database::connect();
     	$texto = "update ensayo_vs_muestra set resultado_mensaje = '$valor', id_responsable= ".session('user')->id." where id_ensayo_vs_muestra = $id_ensayo_vs_muestra";
      	$query = $db->query($texto);     
      
      	almacena_auditoria($id_ensayo_vs_muestra, $valor,'resultado_mensaje');
      
      	$texto = "SELECT COUNT(*) as total  FROM ensayo_vs_muestra "
              . "where id_muestra=(select id_muestra from ensayo_vs_muestra where id_ensayo_vs_muestra=$id_ensayo_vs_muestra ) and ( resultado_mensaje is null or resultado_mensaje ='')";
      	$query = $db->query($texto)->getResult();;  
      
      	$recordSet= $query[0];
      	if($recordSet->total ==0){
            	$texto = "update certificacion set
                            	certificado_estado = 3,
                            	cer_fecha_analisis=now()
                            	where id_muestreo_detalle= (select id_muestra from ensayo_vs_muestra where id_ensayo_vs_muestra=$id_ensayo_vs_muestra ) ";
            	$query = $db->query($texto);
      	}
	}
?>