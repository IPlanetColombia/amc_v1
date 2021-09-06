<?php
use App\Models\Certificacion;
use App\Models\MuestreoDetalle;

	function procesar_registro_fetch($aux_tabla, $aux_columna1, $aux_variable1, 
                                            $aux_columna2=0, $aux_variable2=0, 
                                            $aux_columna3=0, $aux_variable3=0, 
                                            $aux_columna4=0, $aux_variable4=0, 
                                            $aux_columna5=0, $aux_variable5=0, 
                                            $aux_columna6=0, $aux_variable6=0){
		$db = \Config\Database::connect();
		$wheres = [
			$aux_columna1 	=> $aux_variable1,
			$aux_columna2	=> $aux_variable2, 
            $aux_columna3	=> $aux_variable3, 
            $aux_columna4	=> $aux_variable4, 
            $aux_columna5	=> $aux_variable5, 
            $aux_columna6	=> $aux_variable6
		];

	    $data = $db->table($aux_tabla)
	    		->where($wheres)
	    		->get()
	    		->getResult();
	    return $data;

	}

	function auto_incrementar($campo,$tabla){
		$db = \Config\Database::connect();
		$r_ok_autoincrementar = $db->query("SELECT max($campo) as total from $tabla")->getResult();

		// $texto_autoincrementar = "SELECT max($campo) as total from $tabla";
		// $query_autoincrementar = mysql_query($texto_autoincrementar) or die ("error en la operacion autoincrementar ".mysql_error().' '.$texto_autoincrementar);
		// $r_ok_autoincrementar=mysql_fetch_object($query_autoincrementar);
		
		$aux_nro=0;
					
		if (!$r_ok_autoincrementar[0]->total)
			$aux_nro=1;
		else
			$aux_nro=$r_ok_autoincrementar[0]->total+1;		
			
		return $aux_nro;
	
	}

	function generaClave(){
	    //Se define una cadena de caractares. Te recomiendo que uses esta.
	    $cadena = "3468ABCDEFGHIJLMNPQRTUVWXYZabcdefghijmnpqrstuvwxy";
	    //Obtenemos la longitud de la cadena de caracteres
	    $longitudCadena=strlen($cadena);
	     
	    //Se define la variable que va a contener la contraseña
	    $pass = "";
	    //Se define la longitud de la contraseña, en mi caso 10, pero puedes poner la longitud que quieras
	    $longitudPass=13;
	     
	    //Creamos la contraseña
	    for($i=1 ; $i<=$longitudPass ; $i++){
	        //Definimos numero aleatorio entre 0 y la longitud de la cadena de caracteres-1
	        $pos=rand(0,$longitudCadena-1);
	     
	        //Vamos formando la contraseña en cada iteraccion del bucle, añadiendo a la cadena $pass la letra correspondiente a la posicion $pos en la cadena de caracteres definida.
	        $pass .= substr($cadena,$pos,1);
	    }
	    return $pass;
	}

	function construye_codigo_amc($id_muestreo_detalle){
	    $fila_detalle = procesar_registro_fetch('muestreo_detalle', 'id_muestra_detalle', $id_muestreo_detalle);
	    if($fila_detalle[0]->ano_codigo_amc> 0){
	        $aux_codigo =$fila_detalle[0]->ano_codigo_amc.'-'.str_pad($fila_detalle[0]->id_tipo_analisis,2,"0",STR_PAD_LEFT).'-'.str_pad($fila_detalle[0]->id_codigo_amc,4,"0",STR_PAD_LEFT);
	    }else{
	        $fila_analisis = procesar_registro_fetch('muestra_tipo_analisis', 'id_muestra_tipo_analsis', $fila_detalle[0]->id_tipo_analisis);
	        $aux_codigo =$fila_analisis->mue_sigla.' '.$fila_detalle[0]->id_codigo_amc;
	    }
	    return $aux_codigo;                     
	}

	function imprime_detalle_muestras($id_muestreo, $tipo_salida=0){
		$certificados = new Certificacion();
		$certificados = $certificados->where(['id_muestreo' => $id_muestreo])->get()->getResult();

		$tabla = 	'<div id="campo_detalle_muestras">
		                <table class="striped centered">
		                    <thead>
		                        <tr>
		                            <th>#w</th>
		                            <th>Certificado</th>
		                            <th>Tipo de An&aacute;lisis</th>
		                            <th>C&oacute;digo AMC</th>
		                            <th>Norma</th>
		                            <th>Identificaci&oacute;n</th>
		                            <th>Cantidad</th>
		    						<th>Unidad</th>
		                            <th>Opciones</th>
		                        </tr>
		                    </thead>
		                    <tbody>';
		                    	foreach($certificados as $key => $recordSet){
		                    		$key++;
		                    		//formateo de los detalles
		                        	$fila_detalle = procesar_registro_fetch('muestreo_detalle', 'id_muestra_detalle', $recordSet->id_muestreo_detalle);
		                 			//formateo del producto
		                        	$fila_producto = procesar_registro_fetch('producto', 'id_producto', $fila_detalle[0]->id_producto);
		                 			//formateo tipo de amanlisis
		                        	$fila_analisis = procesar_registro_fetch('muestra_tipo_analisis', 'id_muestra_tipo_analsis', $fila_detalle[0]->id_tipo_analisis);
		                        	// return [$fila_detalle , $fila_producto, $fila_analisis];
		                			// formateo de codigo AMC
		                        	/* if($fila_detalle->ano_codigo_amc> 0){
		                             $aux_codigo =$fila_detalle->ano_codigo_amc.'-'.str_pad($fila_detalle->id_tipo_analisis,2,"0",STR_PAD_LEFT).'-'.str_pad($fila_detalle->id_codigo_amc,4,"0",STR_PAD_LEFT);
		                         	}else{
		                             	$aux_codigo =$fila_analisis->mue_sigla.' '.$fila_detalle->id_codigo_amc;
		                         	}*/
		                         	$aux_codigo = construye_codigo_amc($recordSet->id_muestreo_detalle);
                    $tabla .= '	<tr>
                    				<td>'.$key.'</div></td>
                                	<td>'.$recordSet->certificado_nro.'</td>
                                	<td>'.$fila_analisis[0]->mue_nombre.'</td>
                                	<td>'.$aux_codigo.'</td>
                                	<td>'.$fila_producto[0]->pro_nombre.'</td>
                                	<td>'.$fila_detalle[0]->mue_identificacion.'</td>
                                	<td>'.$fila_detalle[0]->mue_cantidad.'</td>
                                	<td>'.$fila_detalle[0]->mue_unidad_medida.'</td>
                                	<td class="action_detail">                                    
                                    	<a href="#" onclick="quitar_detalle('.$recordSet->id_certificacion.')" class="delete_detail_list tooltipped" data-position="left" data-tooltip="Eliminar detalle" data-detalle=""><i class="far fa-trash-alt"></i></a>
                                    	<a class="imprimir_ticket tooltipped" data-position="left" data-tooltip="Imprimir detalle"><i class="fas fa-print"></i></a>
                                    </td>
                    			</tr>
                    ';
		                    	}
                $tabla .=	'	<tr>
				                    <td><strong>&nbsp;</strong></td>
				                    <td><strong>&nbsp;</strong></td>
				                    <td><strong>&nbsp;</strong></td>
				                    <td><strong>&nbsp;</strong></td>
				                    <td><strong>&nbsp;</strong></td>
				                    <td><strong>&nbsp;</strong></td>
				                    <td><strong>&nbsp;</strong></td>
				                    <td><strong>&nbsp;</strong></td>
				                    <td><strong>&nbsp;</strong></td>
				                </tr>
                			</tbody>
		                </table>
		            </div>';
		$button = '	<div class="input-field col s12 centrar_button">
                    	<button id="btn-remicion-guardar" class="btn gradient-45deg-purple-deep-orange border-round">Guardar remición</button>
                    </div>';
		return ['tabla' => $tabla, 'boton' => $button];
	}

	function delete_detail_list($id_certificado){
		$db = \Config\Database::connect();
		$certificados = new Certificacion();
		$fila_certificado = procesar_registro_fetch('certificacion', 'id_certificacion', $id_certificado);
		$certificado = $certificados->where(['id_certificacion' => $id_certificado])->get()->getResult();
		foreach($certificado as $value){
			$muestreo_detalle = new MuestreoDetalle();
			$muestreo_detalle = $muestreo_detalle->where(['id_muestra_detalle' => $value->id_muestreo_detalle])->get()->getResult();
			foreach ($muestreo_detalle as $key => $detalle) {
				$texto2 = "SELECT * FROM muestreo_detalle where id_muestra_detalle=$value->id_muestreo_detalle";
				$result = $db->query($texto2);
			}
			$certificados->where(['id_certificacion' => $id_certificado])->delete();
			$muestreo_detalle = new MuestreoDetalle();
			$muestreo_detalle->where(['id_muestra_detalle' => $value->id_muestreo_detalle])->delete();
		}

		$tabla = imprime_detalle_muestras($fila_certificado[0]->id_muestreo);

		return $tabla['tabla'];

	}