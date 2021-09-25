<?php
use App\Models\Producto;
use App\Models\Parametros;
use App\Models\Ensayo;
use App\Models\Muestreo;
use App\Models\MuestreoDetalle;

	function muestra_tabla($producto, $accion=0){
		$data = new Producto();
		$producto_like = $data
			->like(['pro_nombre' => $producto])->get()->getResult();
		$producto = $data
			->join('norma', 'producto.id_norma = norma.id_norma')
			->where(['pro_nombre' => $producto])->get()->getResult();
		if(empty($producto[0])){
			if(!empty($producto_like)){
				return '';
			}
			return '	<div class="tabla-productos">
							<h1 class="text-center">Norma no encontrada<h1>
						</div class="tabla-productos">';
		}
		$tabla = '
			<div class="tabla-productos">
				<hr>
				<div class="table-content">
					<table>
						<thead>
							<tr>
								<th colspan="2"><h4>Lista de parametros asociados a este producto</h4></th>
							</tr>
							<tr>
								<th>Producto x1: '.$producto[0]->pro_nombre.'</th>
								<th>Norma: '.$producto[0]->nor_nombre.'</th>
							</tr>
						</thead>
						<tbody>
							<table class="striped centered highlight">
								<thead>
									<tr>';
										$ensayo = procesar_registro_fetch('ensayo', 'id_producto', $producto[0]->id_producto);
										foreach($ensayo as $key => $value){
											$parametro = procesar_registro_fetch('parametro', 'id_parametro', $value->id_parametro);
											if($accion <> 0){
					                            if($parametro[0]->par_estado=="Activo"){
				                                    $aux_estado = '<small class="green-text darken-1">'.$parametro[0]->par_estado.' </small>';
				                                }else{
				                                    $aux_estado = '<small class="red-text darken-1">'.$parametro[0]->par_estado.' </small>';
				                                }

					                        }else{
												if($parametro[0]->par_estado == 'Inactivo')
													continue;
					                        }
			                                $tabla .='<th><b>'.$parametro[0]->par_nombre.'</b><br>'.$aux_estado.'</th>';
										}

						$tabla 	.=	'</tr>
								</thead>
								<tbody>
									<tr>';
									foreach($ensayo as $key => $value){
										$parametro = procesar_registro_fetch('parametro', 'id_parametro', $value->id_parametro);
										if($accion<>0){// cero para ingreso de remisiones diferentes para editar
	                                
	                           			}else{
											if($parametro[0]->par_estado == "Inactivo")
		                                    	continue;
		                                }
		                                $tabla .= '<td>'.$value->med_valor_min.' - '.$value->med_valor_max.'</td>';
									}
					$tabla .= 	'	</tr>
									<tr>';

									$aux_cols = 0;
									foreach($ensayo as $value){
										$parametro = procesar_registro_fetch('parametro', 'id_parametro', $value->id_parametro);
										if($accion<>0){// cero para ingreso de remisiones diferentes para editar
	                                
	                           			}else{
											if($parametro[0]->par_estado == "Inactivo")
		                                    	continue;
		                                }

		                                if($accion <> 0){
				                            $filaChecked = procesar_registro_fetch('ensayo_vs_muestra', 'id_muestra', $accion, 'id_ensayo', $value->id_ensayo);

				                            if(isset($filaChecked[0]->id_ensayo_vs_muestra)){//si existe se selecciona
				                                $checked = "checked";
				                            }else{
				                                $checked = "";
				                            }

				                        }else{
				                            $checked = "checked";
				                        }
		                                $tabla .='<td class="action">
		                                		<label>
			                                		<input type="checkbox" name="frm_chk_'.$value->id_ensayo.'" id="frm_chk_'.$value->id_ensayo.'" '.$checked.'/>
			                                		<span></span>
		                                		</label>
		                                	</td>';
	                        			$aux_cols++;
									}
					if($accion<>0){// cero para ingreso de remisiones diferentes para editar
						$muestreoDetalle = procesar_registro_fetch('muestreo_detalle', 'id_muestra_detalle', $accion);
	                	$certificacion = procesar_registro_fetch('certificacion', 'id_muestreo_detalle', $muestreoDetalle[0]->id_muestra_detalle);

	                	$mensaje = "Editar muestra";
	                	$inputIdMuestraDetalle = '
	                		<label>
	                			<input type="checkbox" name="frm_elimina_resultado" id="frm_elimina_resultado" value="SI" checked/>
	                			<span>Eliminar resultado ingresados.</span>
	                		</label>
							<input type="hidden" name="frm_id_muestra_detalle" id="frm_id_muestra_detalle" value="'.$accion.'"/>
	                    	<input type="hidden" name="frm_id_codigo_amc" id="frm_id_codigo_amc" value="'.$muestreoDetalle[0]->id_codigo_amc.'"/>
	                    	<input type="hidden" name="frm_id_certificado" id="frm_id_certificado" value="'.$certificacion[0]->certificado_nro.'"/>
	                    	<input type="hidden" name="frm_id_muestreo" id="frm_id_muestreo" value="'.$certificacion[0]->id_muestreo.'"/>';     
	                }else{
						$mensaje = "Agregar a lista";
		               	$inputIdMuestraDetalle = '';
		            }

	            	$aux_checked_solido 	= 'checked';
	            	$aux_checked_liquido 	= '';
		            if($muestreoDetalle[0]->mue_unidad_medida === 'liquida'){
			            $aux_checked_solido 	= '';
			            $aux_checked_liquido 	= 'checked';
		            }

					$tabla .=	'	</tr>
								</tbody>
							</table>
						</tbody>
					</table>
				</div>
			</div>
			<div class="row mt-2 mb-2">
				<div class="col s12 centrar_button">
                	<b style="width: 100%; text-align: center;">Unidad de Medida</b><br>
                	<label>
                		<input  type="radio" id="frm_unidad_parametro" name="frm_unidad_parametro" value="solida"'.$aux_checked_solido.'>
                		<span>S&oacute;lidas</span>
                	</label>
                	<label>
                		<input  type="radio" id="frm_unidad_parametro" name="frm_unidad_parametro" value="liquida"'.$aux_checked_liquido.'>
                		<span>Liquidas</span>
                	</label>
                </div>
    			<div class="col s12 centrar_button">
                	<label>
                		<input type="checkbox" value="1" name="frm_bandera_certificado" id="frm_bandera_certificado" checked>
                		<span>Un certificado</span>
                	</label>
                </div>
        		<div class="col s12 centrar_button" id="campo_botton_agregar">
        			<button id="btn-muestreo-form" class="btn gradient-45deg-purple-deep-orange border-round agregar_lista">'.$mensaje.'</button>
        			<input type="hidden" name="frm_id_forma" id="frm_id_forma" value="frm_form_muestra"/>
        		</div>
            </div>
            <div class="row">
            	<div class="col l12">
		            '.$inputIdMuestraDetalle.'
		        </div>
		    </div>
		';
	    return $tabla;
	}

	function detalles_tabla($forms, $accion=0){
		$db = \Config\Database::connect();
        $id_producto = procesar_registro_fetch('producto', 'pro_nombre', $forms['frm_producto']);
        $forms['frm_producto'] = $id_producto[0]->id_producto;
		$salida = '';
		if($accion == 0){ // Creacion
			$forms['frm_fecha_recepcion'] = $forms['frm_fecha_muestra'].' '.$forms['frm_hora_muestra'];
			$anio_= date("y");
	        $tabla = 'muestreo_detalle where ano_codigo_amc='.$anio_;
	        $aux_codigo_amc = auto_incrementar('id_codigo_amc',$tabla);

	        if ($forms['frm_bandera_certificado'] == 1) //un certificado
	            $aux_nro_certificado = auto_incrementar('certificado_nro','certificacion');
	        else
	            $aux_nro_certificado = auto_incrementar('certificado_nro','certificacion')-1;

	        
	        $data_detalle = [
	         	'id_tipo_analisis'				=> $forms['frm_analisis'],
				'id_codigo_amc'					=> $aux_codigo_amc,
				'mue_procedencia'				=> $forms['frm_procedencia'],
				'mue_identificacion'			=> $forms['frm_identificacion'],
				'mue_lote'						=> $forms['frm_lote'],
				'mue_fecha_produccion'			=> $forms['frm_fecha_produccion'],
				'mue_fecha_vencimiento'			=> $forms['frm_fecha_vencimiento'],
				'mue_temperatura_muestreo'		=> $forms['frm_tmp_muestreo'],
				'mue_temperatura_laboratorio'	=> $forms['frm_tmp_recepcion'],
				'mue_condiciones_recibe'		=> $forms['frm_condiciones_recibido'],
				'mue_cantidad'					=> $forms['frm_cantidad'],
				'id_producto'					=> $forms['frm_producto'],
				'mue_momento_muestreo'			=> $forms['frm_momento_muestreo'],
				'mue_parametro'					=> $forms['frm_parametro'],
				'mue_area'						=> $forms['frm_area'],
				'mue_tipo_muestreo'				=> $forms['frm_tipo_muestreo'],
				'ano_codigo_amc'				=> $anio_,
				'mue_adicional'					=> $forms['frm_adicional'],
				'mue_unidad_medida'				=> $forms['frm_unidad_parametro']
	        ];
	        $muestreo_detalle = new MuestreoDetalle();
	        $muestreo_detalle->insert($data_detalle);
	        $salida .= "<br><font color=red>Muestra detalle correctamente</font>";
	        $aux_id_muestra_detalle = $muestreo_detalle->getInsertID();
		}else{
			if($forms['frm_elimina_resultado']=='SI'){// ajuste del 6 de septiembre de 2017
	            $texto = "delete from ensayo_vs_muestra where id_muestra=".$forms['id_muestra_detalle']." ";
	            if ($db->simpleQuery($texto)){
				    $salida .= "<br><font color=red>ensayo_vs_muestra correctamente</font>";
				}
	        }
	        //codigo amc de la muestra
            $aux_codigo_amc = $forms['frm_id_codigo_amc'];
            $muestreoDetalle = new MuestreoDetalle();
            $data = [
            	'id_tipo_analisis'				=> $forms['frm_analisis'],
            	'id_codigo_amc' 				=> $aux_codigo_amc,
				'mue_procedencia' 				=> $forms['frm_procedencia'],
				'mue_identificacion' 			=> $forms['frm_identificacion'],
				'mue_lote' 						=> $forms['frm_lote'],
				'mue_fecha_produccion' 			=> $forms['frm_fecha_produccion'],
				'mue_fecha_vencimiento' 		=> $forms['frm_fecha_vencimiento'],
				'mue_temperatura_muestreo' 		=> $forms['frm_tmp_muestreo'],
				'mue_temperatura_laboratorio' 	=> $forms['frm_tmp_recepcion'],
				'mue_condiciones_recibe' 		=> $forms['frm_condiciones_recibido'],
				'mue_cantidad' 					=> $forms['frm_cantidad'],
				'id_producto' 					=> $forms['frm_producto'],
				'mue_momento_muestreo' 			=> $forms['frm_momento_muestreo'],
				'mue_parametro' 				=> $forms['frm_parametro'],
				'mue_area' 						=> $forms['frm_area'],
				'mue_tipo_muestreo' 			=> $forms['frm_tipo_muestreo'],
				'mue_adicional' 				=> $forms['frm_adicional'],
				'mue_unidad_medida'				=> $forms['frm_unidad_parametro']
            ];
            $muestreoDetalle->set($data)->where(['id_muestra_detalle' => $forms['id_muestra_detalle']])->update();
            $aux_id_muestra_detalle = $forms['id_muestra_detalle'];


        	//Certificaciones
            $aux_nro_certificado = $forms['frm_id_certificado'];

        	//
            $forms['frm_id_remision'] = $forms['frm_id_muestreo'];
		}

		if ($forms['frm_id_remision'] == 0){
			$data = [
				'id_cliente' 			=> $forms['frm_nombre_empresa2'], 
				'mue_estado' 			=> $forms['frm_estado_remision'], 
				'mue_fecha_muestreo' 	=> $forms['frm_fecha_muestra'].' '.$forms['frm_hora_muestra'], 
				'mue_fecha_recepcion' 	=> $forms['frm_fecha_recepcion'], 
				'mue_fecha_analisis' 	=> $forms['frm_fecha_analisis'], 
				'mue_fecha_informe' 	=> $forms['frm_fecha_informe'], 
				'mue_entrega_muestra' 	=> $forms['frm_entrega'], 
				'mue_recibe_muestra' 	=> $forms['frm_recibe'], 
				'mue_responsable_op' 	=> $forms['frm_responsable'], 
				'mue_observaciones' 	=> $forms['frm_observaciones'], 
				'mue_subtitulo' 		=> $forms['frm_nombre_empresa_subtitulo'] 
			];
			$muestreo = new Muestreo();
			$muestreo->insert($data);
			$forms['frm_id_remision'] = $muestreo->getInsertID();
		}else{
			//ya existe x consiguiente se actualizan valores de encabezado
			$data_update = [
				'id_cliente' 			=> $forms['frm_nombre_empresa2'],
				'mue_fecha_muestreo' 	=> $forms['frm_fecha_muestra'].' '.$forms['frm_hora_muestra'], 
				'mue_fecha_recepcion' 	=> $forms['frm_fecha_recepcion'],
				'mue_entrega_muestra' 	=> $forms['frm_entrega'], 
				'mue_recibe_muestra' 	=> $forms['frm_recibe'],
				'mue_observaciones' 	=> $forms['frm_observaciones'], 
				'mue_subtitulo' 		=> $forms['frm_nombre_empresa_subtitulo'] 
			];
			$muestreo = new Muestreo();
            $muestreo->set($data_update)
            	->where(['id_muestreo' => $forms['frm_id_remision']])
            	->update();
		}


        $salida .= "<br><font color=red>sale 2</font>";

        $ensayo = new Ensayo();
        $ensayo_result = $ensayo->where(['id_producto' => $forms['frm_producto']])
        			->get()->getResult();
        foreach($ensayo_result as $recordSet){
        	if(isset($forms['frm_chk_'.$recordSet->id_ensayo])){
        		//si exite valor
				// confirmamos que si fue seleccionada la bandera de eliminar resultados
				// si fue seleccionada debemos validar que no existan el registro de resultados
				if($forms['frm_elimina_resultado']<>'SI'){// ajuste del 6 de septiembre de 2017
					$aux_existe_resultados = procesar_registro_fetch('ensayo_vs_muestra', 'id_ensayo', $recordSet->id_ensayo, 'id_muestra', $aux_id_muestra_detalle);
					if(!$aux_existe_resultados[0]->id_ensayo){
						$sql_ensayo = "insert into ensayo_vs_muestra (id_ensayo, id_muestra)
                                            values ($recordSet->id_ensayo, $aux_id_muestra_detalle)";
						if ($db->simpleQuery($sql_ensayo)){
						    $salida .= "<br><font color=red>ensayo_vs_muestra correctamente</font>";
						}
						
				 	}
				}else{					 
					$sql_ensayo = "insert into ensayo_vs_muestra (id_ensayo, id_muestra)
                                            values ($recordSet->id_ensayo, $aux_id_muestra_detalle)";
					if ($db->simpleQuery($sql_ensayo)){
					    $salida .= "<br><font color=red>ensayo_vs_muestra correctamente</font>";
					}
				}
        	}else{//si no esta seleccionado lo eliminamos
    			$texto_elimina_enayo = "delete from ensayo_vs_muestra where id_muestra=$aux_id_muestra_detalle and id_ensayo=$recordSet->id_ensayo ";
				if ($db->simpleQuery($texto_elimina_enayo)){
				    $salida .= "<br><font color=red>elimina ensayo_vs_muestra correctamente</font>";
				}
         	}
        }
        $aux_clave_preinforme = generaClave();
        $aux_clave_informe = generaClave();
		
        /*
         * mejora quitar de los encriptados los ceros, letra o . la letra l el numero uno y el punto
         */
        $aux_clave_preinforme = str_replace("1", "", $aux_clave_preinforme);
        $aux_clave_preinforme = str_replace("l", "", $aux_clave_preinforme);
        $aux_clave_preinforme = str_replace("O", "", $aux_clave_preinforme);
        $aux_clave_preinforme = str_replace("0", "", $aux_clave_preinforme);
        $aux_clave_preinforme = str_replace("k", "", $aux_clave_preinforme);
        $aux_clave_preinforme = str_replace("K", "", $aux_clave_preinforme);
        $aux_clave_preinforme = str_replace("2", "", $aux_clave_preinforme);
        $aux_clave_preinforme = str_replace("z", "", $aux_clave_preinforme);
        $aux_clave_preinforme = str_replace("6", "", $aux_clave_preinforme);
        $aux_clave_preinforme = str_replace("G", "", $aux_clave_preinforme);

        //$aux_clave_preinforme = str_replace($search, $replace, $subject);
        $aux_clave_informe = str_replace("1", "", $aux_clave_informe);
        $aux_clave_informe = str_replace("l", "", $aux_clave_informe);
        $aux_clave_informe = str_replace("O", "", $aux_clave_informe);
        $aux_clave_informe = str_replace("0", "", $aux_clave_informe);
        $aux_clave_informe = str_replace("k", "", $aux_clave_informe);
        $aux_clave_informe = str_replace("K", "", $aux_clave_informe);
        $aux_clave_informe = str_replace("2", "", $aux_clave_informe);
        $aux_clave_informe = str_replace("z", "", $aux_clave_informe);
        $aux_clave_informe = str_replace("6", "", $aux_clave_informe);
        $aux_clave_informe = str_replace("G", "", $aux_clave_informe);

        if($accion == 0){ // Insertamos certificado
	        $sql_certificacion = "insert into certificacion
	                            (id_muestreo, id_muestreo_detalle, clave_documento_pre, clave_documento_final, 
	                            certificado_nro, cer_fecha_analisis, cer_fecha_preinforme, cer_fecha_informe)
	                                values
	                           ($forms[frm_id_remision], $aux_id_muestra_detalle, '$aux_clave_preinforme','$aux_clave_informe',
	                            $aux_nro_certificado,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00')";
	        if ($db->simpleQuery($sql_certificacion)){
	             $salida .= "<br><font color=red>Muestra detalle correctamente</font>";
	        }
        }
    	$salida = imprime_detalle_muestras($forms['frm_id_remision'], $accion);

		return ['tabla' => $salida['tabla'], 'frm_id_remision' => $forms['frm_id_remision'], 'boton' => $salida['boton']];
	}

	function mensaje_resultado($certificado_nro){
		$resultados = procesar_registro_fetch('mensaje_resultado',0,0);
		$mensajes = procesar_registro_fetch('mensaje',0,0);
		$consulta = procesar_registro_fetch('certificacion_vs_mensaje', 'id_certificacion', $certificado_nro);
		$tabla = '';
		if(!empty($consulta[0])){
			$tabla = '<div class="concepto">
						<hr>
						<h4>Mensajes del resultado </h4>
			            <div class="row">
			            	<div class="col s12 l6">
										<input type="hidden" name="buscar" value="5">
										<input type="hidden" id="frm_id_certificado" name="frm_id_certificado" value="'.$certificado_nro.'">
			            				<select id="frm_mensaje_resultado" name="frm_mensaje_resultado" class="validate">
			            					<option value="">Seleccione mensaje</option>';
			            			foreach($resultados as $key => $resultado){
			            				$selected = ($resultado->id_mensaje == $consulta[0]->id_mensaje_resultado) ? 'selected' : '';
			            		$tabla .= '	<option value="'.$resultado->id_mensaje.'"'.$selected.'>'.$resultado->mensaje_titulo.'</option>';
			            			}
			            		$tabla .='		</select>
			            	</div>
			            	<div class="col s12 l6">
			            				<select id="frm_mensaje_observacion" name="frm_mensaje_observacion" class="validate">
			            					<option value="">Seleccione observación</option>';
			            				foreach($mensajes as $mensaje){
			            					$selected = ($mensaje->id_mensaje == $consulta[0]->id_mensaje_comentario) ? 'selected':'';
			            					$tabla.='<option value="'.$mensaje->id_mensaje.'" '.$selected.'>'.$mensaje->mensaje.'</option>';
			            				}
			            		$tabla .= '		</select>
			            	</div>
	        				<div class="input-field col s12 centrar_button">
				                <button class="btn gradient-45deg-purple-deep-orange border-round">
				                    Cambiar mensaje
				                </button>
				            </div>
				        </div>
				    </div>';
		}
		return $tabla;
	}

	function guardar_remision($form, $accion = 0){
		$muestreo = new Muestreo();
		$muestreo->set([
			'mue_estado'			=> 1,
			'mue_recibe_muestra'	=> $form['frm_recibe'],
			'mue_entrega_muestra'	=> $form['frm_entrega'],
			'mue_observaciones' 	=> $form['frm_observaciones']
		])->where(['id_muestreo' => $form['frm_id_remision']])->update();
		return [
			'mensaje' 	=> 'Remisión guardada correctamente',
			'fecha' 	=> date('Y-m-d'),
			'hora' 		=> date('H:i:s')
		];
	}