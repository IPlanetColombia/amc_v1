<?php
use App\Models\Certificacion;
use Config\Services;

	function lista_resultados($id_certificado, $que_mostrar){
		$db = \Config\Database::connect();
		$certificados = new Certificacion();
		$certificados = $certificados->select('*')
			->join('muestreo', 'certificacion.id_muestreo = muestreo.id_muestreo')
			->join('usuario', 'usuario.id = muestreo.id_cliente')
			->join('muestreo_detalle', 'certificacion.id_muestreo_detalle = muestreo_detalle.id_muestra_detalle')
			->join('producto', 'muestreo_detalle.id_producto = producto.id_producto')
			->join('muestra_tipo_analisis', 'muestreo_detalle.id_tipo_analisis = muestra_tipo_analisis.id_muestra_tipo_analsis')
			->where(['certificado_nro' => $id_certificado])
			->orderBy('id_certificacion', 'DESC')
			->get()->getResult();
			//formateo detalle
            //     $fila_detalle = procesar_registro_fetch('muestreo_detalle', 'id_muestra_detalle', $certificado->id_muestreo_detalle);
            //     $fila_detalle = $fila_detalle[0];
            // //formateo de producto
            //     $fila_producto = procesar_registro_fetch('producto', 'id_producto', $fila_detalle->id_producto);
            //     $fila_producto = $fila_producto[0];
            // //formateo tipo de amanlisis
            //     $fila_analisis = procesar_registro_fetch('muestra_tipo_analisis', 'id_muestra_tipo_analsis', $fila_detalle->id_tipo_analisis);
            //     $fila_analisis = $fila_analisis[0];
		// $fila_certificado = procesar_registro_fetch('certificacion', 'certificado_nro', $id_certificado);
	 //    //exit($id_certificado);
	 //    $fila_muestreo = procesar_registro_fetch('muestreo', 'id_muestreo', $fila_certificado[0]->id_muestreo);
	 //    //formateo de cliente
  //       $fila_cliente = procesar_registro_fetch('usuario', 'id', $fila_muestreo[0]->id_cliente);
        if (!$certificados[0]->mue_subtitulo){
            $certificados[0]->mue_subtitulo = '-xxx-';
        }
        $certificados[0]->mue_subtitulo = formatea_tildes($certificados[0]->mue_subtitulo);
        $tabla_1 = '
        	<div class="row">
        		<div class="col s12 l12">
					<h5 class="center-align"><b>Resultado para el certificado '.$id_certificado.'</b></h5>
        			<table class="striped">
        				<thead>
        					<tr>
				            	<th><b>Procedencia</b></th>
				                <th>'.$certificados[0]->name.'</th>
				                <th><b>Muestreo</b></th>
				                <th id="campo_fecha_muestreo"><p ondblclick="editar_campos(`date`,`campo_fecha_muestreo`,`'.date('Y-m-d', strtotime($certificados[0]->mue_fecha_muestreo)).'`, `frm_fecha_muestra`, `mue_fecha_muestreo`, `muestreo` , `'.$certificados[0]->id_muestreo.'`)">'.$certificados[0]->mue_fecha_muestreo.'</p></th>
				            </tr>
				            <tr>
				           		<th><b>Sub titulo</b></th>
				                <th id="campo_sub_titulo"><p ondblclick="editar_campos(`text`, `campo_sub_titulo`,`'.$certificados[0]->mue_subtitulo.'`, `frm_nombre_empresa_subtitulo`, `mue_subtitulo`, `muestreo` ,`'.$certificados[0]->id_muestreo.'`)">'.$certificados[0]->mue_subtitulo.'</p></th>
				                <th><b>Registro</b></th>
				                <th id="campo_fecha_recepcion"><p ondblclick="editar_campos(`date`,`campo_fecha_recepcion`,`'.date('Y-m-d', strtotime($certificados[0]->mue_fecha_recepcion)).'`, `frm_fecha_recepcion`, `mue_fecha_recepcion`, `muestreo` ,`'.$certificados[0]->id_muestreo.'`)">'.$certificados[0]->mue_fecha_recepcion.'</p></th>
				            </tr>
				            <tr>
				            	<th><b>Nit</b></th>
				                <th>'.$certificados[0]->id.'</th>
				                <th><b>Analisis</b></th>
				                <th></th>
				            </tr>
				            <tr>
				            	<th><b>&nbsp;</b></th>
				                <th><b>&nbsp;</b></th>
				                <th><b>Elaboracion del Pre-informe</b></th>
				                <th id="campo_fecha_preinforme"><p ondblclick="editar_campos(`date`,`campo_fecha_preinforme`,`'.date('Y-m-d', strtotime($certificados[0]->cer_fecha_preinforme)).'`, `frm_fecha_preinforme`, `cer_fecha_preinforme`, `certificacion` ,`'.$certificados[0]->id_muestreo.'`)">'.$certificados[0]->cer_fecha_preinforme.'</p></th>
				            </tr>
				            <tr>
				            	<th><b>&nbsp;</b></th>
				                <th><b>&nbsp;</b></th>
				                <th><b>Elaboracion del informe</b></th>
				                <th id="campo_fecha_informe"><p ondblclick="editar_campos(`date`,`campo_fecha_informe`,`'.date('Y-m-d', strtotime($certificados[0]->cer_fecha_informe)).'`, `frm_fecha_informe`, `cer_fecha_informe`, `certificacion` ,`'.$certificados[0]->id_muestreo.'`)">'.$certificados[0]->cer_fecha_informe.'</p></th>
				            </tr>
        				</thead>
        			</table>
        		</div>
        	</div>';


        // $certificados = procesar_registro_fetch('certificacion', 'certificado_nro', $id_certificado);
        foreach ($certificados as $key => $certificado) {        	
            if (!$certificado->mue_procedencia)
                $certificado->mue_procedencia='-xxx-';
            if (!$certificado->mue_identificacion)
                $certificado->mue_identificacion='-xxx-';
            if (!$certificado->mue_lote)
                $certificado->mue_lote='-xxx-';
            if (!$certificado->mue_fecha_produccion)
                $certificado->mue_fecha_produccion='-xxx-';
            if (!$certificado->mue_fecha_vencimiento)
                $certificado->mue_fecha_vencimiento='-xxx-';
            if (!$certificado->mue_temperatura_muestreo)
                $certificado->mue_temperatura_muestreo='-xxx-';
            if (!$certificado->mue_temperatura_laboratorio)
                $certificado->mue_temperatura_laboratorio='-xxx-';
            if (!$certificado->mue_condiciones_recibe)
                $certificado->mue_condiciones_recibe='-xxx-';
            if (!$certificado->mue_cantidad)
                $certificado->mue_cantidad='-xxx-';
            // Encabezado
            $tabla_2 .= '
            	<div class="row">
	        		<div class="col s12 l12">
						<h5 class="center-align"><b>Muestra '. construye_codigo_amc($certificado->id_muestra_detalle).'</b></h5>
	        			<table class="striped">
	        				<thead>
	        					<tr>
					            	<th><b>Procedencia</b></th>
					                <th id="campo_procedencia'.$certificado->id_muestra_detalle.'"><p ondblclick="editar_campos(`text`,`campo_procedencia'.$certificado->id_muestra_detalle.'`,`'.$certificado->mue_procedencia.'`, `frm_procedencia`, `mue_procedencia`, `muestreo_detalle` ,`'.$certificado->id_muestra_detalle.'`)">'.$certificado->mue_procedencia.'</p></th>
					                <th><b>Identificaci&oacute;n</b></th>
					                <th id="campo_identificacion'.$certificado->id_muestra_detalle.'"><p ondblclick="editar_campos(`text`,`campo_identificacion'.$certificado->id_muestra_detalle.'`,`'.$certificado->mue_identificacion.'`, `frm_identificacion`, `mue_identificacion`, `muestreo_detalle` ,`'.$certificado->id_muestra_detalle.'`)">'.$certificado->mue_identificacion.'</p></th>
					           		<th><b>No. lote</b></th>
					                <th id="campo_lote'.$certificado->id_muestra_detalle.'"><p ondblclick="editar_campos(`text`,`campo_lote'.$certificado->id_muestra_detalle.'`,`'.$certificado->mue_lote.'`, `frm_lote`, `mue_lote`, `muestreo_detalle` ,`'.$certificado->id_muestra_detalle.'`)">'.$certificado->mue_lote.'</p></th>
					            </tr>
					            <tr>
					                <th><b>Fecha Producci&oacute;n</b></th>
					                <th id="campo_fecha_produccion'.$certificado->id_muestra_detalle.'"><p ondblclick="editar_campos(`date`,`campo_fecha_produccion'.$certificado->id_muestra_detalle.'`,`'.date('Y-m-d', strtotime($certificado->mue_fecha_produccion)).'`, `frm_fecha_produccion`, `mue_fecha_produccion`, `muestreo_detalle` ,`'.$certificado->id_muestra_detalle.'`)">'.$certificado->mue_fecha_produccion.'</p></th>
					            	<th><b>Fecha vencimiento</b></th>
					                <th id="campo_fecha_vencimiento'.$certificado->id_muestra_detalle.'"><p ondblclick="editar_campos(`date`,`campo_fecha_vencimiento'.$certificado->id_muestra_detalle.'`,`'.date('Y-m-d', strtotime($certificado->mue_fecha_vencimiento)).'`, `frm_fecha_vencimiento`, `mue_fecha_vencimiento`, `muestreo_detalle` ,`'.$certificado->id_muestra_detalle.'`)">'.$certificado->mue_fecha_vencimiento.'</p></th>
					                <th><b>Temp. Muestreo</b></th>
					                <th id="campo_temperatura_muestreo'.$certificado->id_muestra_detalle.'"><p ondblclick="editar_campos(`text`,`campo_temperatura_muestreo'.$certificado->id_muestra_detalle.'`,`'.$certificado->mue_temperatura_muestreo.'`, `frm_temperatura_muestreo`, `mue_temperatura_muestreo`, `muestreo_detalle` ,`'.$certificado->id_muestra_detalle.'`)">'.$certificado->mue_temperatura_muestreo.'</p></th>
					            </tr>
					            <tr>
					                <th><b>Temp. recepci&oacute;n</b></th>
					                <th id="campo_temperatura_laboratorio'.$certificado->id_muestra_detalle.'"><p ondblclick="editar_campos(`text`,`campo_temperatura_laboratorio'.$certificado->id_muestra_detalle.'`,`'.$certificado->mue_temperatura_laboratorio.'`, `frm_temperatura_laboratorio`, `mue_temperatura_laboratorio`, `muestreo_detalle` ,`'.$certificado->id_muestra_detalle.'`)">'.$certificado->mue_temperatura_laboratorio.'</p></th>
					            	<th><b>Condiciones de recibido</b></th>
					                <th id="campo_condiciones_recibe'.$certificado->id_muestra_detalle.'"><p ondblclick="editar_campos(`text`,`campo_condiciones_recibe'.$certificado->id_muestra_detalle.'`,`'.$certificado->mue_condiciones_recibe.'`, `frm_condiciones_recibe`, `mue_condiciones_recibe`, `muestreo_detalle` ,`'.$certificado->id_muestra_detalle.'`)">'.$certificado->mue_condiciones_recibe.'</p></th>
					                <th><b>Cantidad</b></th>
					                <th id="campo_cantidad'.$certificado->id_muestra_detalle.'"><p ondblclick="editar_campos(`text`,`campo_cantidad'.$certificado->id_muestra_detalle.'`,`'.$certificado->mue_cantidad.'`, `frm_cantidad`, `mue_cantidad`, `muestreo_detalle` ,`'.$certificado->id_muestra_detalle.'`)">'.$certificado->mue_cantidad.'</p></th>
					            </tr>
					            <tr>
					                <th><b>An&aacute;lisis solicitado</b></th>
					                <th colspan="2">'.$certificado->mue_nombre.'</th>
					            	<th><b>Producto</b></th>
					                <th colspan="2">'.$certificado->pro_nombre.'</th>
					            </tr>
	        				</thead>
	        			</table>
	        		</div>
	        	</div>
            ';
            // Parametros
            $ensayos = procesar_registro_fetch('ensayo', 'id_producto', $certificado->id_producto);
            $parametros_aux = [];
            $tabla_3 .= '
            	<div class="row">
	        		<div class="col s12 l12">
	        			<table class="striped centered">
	        				<thead>
	        					<tr class="cyan lighten-5">
	        						<th class="black-text"><b>Titulo</b></th>';
	        				foreach ($ensayos as $key => $ensayo) {
	        					$parametro = procesar_registro_fetch('parametro', 'id_parametro', $ensayo->id_parametro);  
	        					if ($parametro[0]->par_estado == 'Activo') {
	        						$parametros_aux[$key] = $parametro[0];
	        						$tecnica   = procesar_registro_fetch('tecnica', 'id_tecnica', $parametro[0]->id_tecnica);
	        						$tabla_3 .= '
		        						<th class="black-text" style="min-width: 130px">
		        							<b>'.$parametro[0]->par_nombre.'</b>
		        							<br>
		        							<small class="grey-text text-darken-4">'.$tecnica[0]->nor_nombre.'</small>
		        							<br>
		        							<small class="green-text text-darken-4"><b>'.$parametro[0]->par_estado.'</b></small>
		        						</th>';
	        					}
	        				}
	        					
	        $tabla_3 .= '		</tr>
	        				</thead>
	        				<tbody>
	        					<tr>
	        						<td class="cyan lighten-5 black-text"><b>Rango</b></td>';
	        						foreach ($ensayos as $key => $ensayo) {
	        							$parametro = procesar_registro_fetch('parametro', 'id_parametro', $ensayo->id_parametro);  
				        					if ($parametro[0]->par_estado == 'Activo') {
				        						$tabla_3 .= '
					        						<td>
					        							<b>'.$ensayo->med_valor_min.' - '.$ensayo->med_valor_max.'</b>
					        						</td>';
				        					}
	        						}
	        	$tabla_3.='		</tr>';

	        	$tabla_3.='		<tr>
	        						<td class="cyan lighten-5 black-text"><b>Unidades</b></td>';
	        						foreach ($ensayos as $key => $ensayo) {
	        							$parametro = procesar_registro_fetch('parametro', 'id_parametro', $ensayo->id_parametro);  
				        					if ($parametro[0]->par_estado == 'Activo') {
				        						$aux = 'No aplica';
				        						if($certificado->mue_unidad_medida){
						                             $aux = ($certificado->mue_unidad_medida == 'solida')?$parametro[0]->unidad_solida:$parametro[0]->unidad_liquida;
						                             // si es vacio el campo se mostrara la unidad de medida
						                             if(!trim($aux)){
						                             
						                                 $aux= ($certificado->mue_unidad_medida == 'solida')?'gr':'ml';
						                             }
						                         }
						                         
						                        $aux_incertidumbre = ($parametro[0]->incertidumbre) ? $parametro[0]->incertidumbre : 'No aplica';
				        						$tabla_3 .= '
					        						<td>
					        							<b>'.$aux.'</b>
					        						</td>';
				        					}
	        						}
	        	$tabla_3.='		</tr>';

	        	$tabla_3.='		<tr>
	        						<td class="cyan lighten-5 black-text"><b>μ</b></td>';
	        						foreach ($ensayos as $key => $ensayo) {
	        							$parametro = procesar_registro_fetch('parametro', 'id_parametro', $ensayo->id_parametro);  
				        					if ($parametro[0]->par_estado == 'Activo') {
				        						$aux = 'No aplica';
				        						if($certificado->mue_unidad_medida){
						                             $aux = ($certificado->mue_unidad_medida == 'solida')?$parametro[0]->unidad_solida:$parametro[0]->unidad_liquida;
						                             // si es vacio el campo se mostrara la unidad de medida
						                             if(!trim($aux)){
						                             
						                                 $aux= ($certificado->mue_unidad_medida == 'solida')?'gr':'ml';
						                             }
						                         }
						                         
						                        $aux_incertidumbre = ($parametro[0]->incertidumbre) ? $parametro[0]->incertidumbre : 'No aplica';
				        						$tabla_3 .= '
					        						<td>
					        							<b>'.$aux_incertidumbre.'</b>
					        						</td>';
				        					}
	        						}
	        	$tabla_3.='		</tr>';

	        	$tabla_3.='		<tr>
	        						<td class="cyan lighten-5 black-text"><b>Regla</b></td>';
	        						foreach ($ensayos as $key => $ensayo) {
	        							$parametro = procesar_registro_fetch('parametro', 'id_parametro', $ensayo->id_parametro);  
				        					if ($parametro[0]->par_estado == 'Activo') {
				        						$ensayo_vs_muestra = procesar_registro_fetch('ensayo_vs_muestra', 'id_ensayo', $ensayo->id_ensayo, 'id_muestra', $certificado->id_muestra_detalle);
				        						$aux_id_ensayo_vs_muestra = $ensayo_vs_muestra[0]->id_ensayo_vs_muestra;
                    							$aux_id_regla = $ensayo_vs_muestra[0]->id_regla;
				        						$tabla_3 .= '
					        						<td>
					        							<div class="input-field col s12">
														    <select name="frm_regla_'.$aux_id_ensayo_vs_muestra.'"
														    	onChange="cambiar_campos(`text`,'.$aux_id_ensayo_vs_muestra.',
														    		this.value,
														    		 `frm_regla_'.$aux_id_ensayo_vs_muestra.'`, `id_regla`, `ensayo_vs_muestra`, `'.$aux_id_ensayo_vs_muestra.'` )">
														      <option value="0">No aplica</option>';
														      $reglas = procesar_registro_fetch('regla' ,'estado', 'Activa');
														      foreach ($reglas as $key => $regla){
										                        	$aux_checked = $regla->id_regla == $aux_id_regla ? "selected":'';
										                        	$tabla_3.='<option value="'.$regla->id_regla.'" '.$aux_checked.'>'.$regla->nombre.'</option>';
														      }

											$tabla_3.='	    </select>
															<small id="campo_regla_'.$aux_id_ensayo_vs_muestra.'"></small>
														  </div>
					        						</td>';
				        					}
	        						}
	        	$tabla_3.='		</tr>';

	        	$tabla_3.='		<tr>
	        						<td class="cyan lighten-5 black-text"><b>Registros</b></td>';
	        						foreach ($ensayos as $key => $ensayo) {
	        							$parametro = procesar_registro_fetch('parametro', 'id_parametro', $ensayo->id_parametro);  
				        					if ($parametro[0]->par_estado == 'Activo') {
				        						$ensayo_vs_muestra = procesar_registro_fetch('ensayo_vs_muestra', 'id_ensayo', $ensayo->id_ensayo, 'id_muestra', $certificado->id_muestra_detalle);
				        						if(isset($ensayo_vs_muestra[0]->id_ensayo_vs_muestra)){
					        						$tabla_3 .= '
						        						<td>
						        							<b>'.$ensayo_vs_muestra[0]->resultado_analisis.' &nbsp;</b><br>
                                    						<b>'.$ensayo_vs_muestra[0]->resultado_analisis2.' &nbsp;</b>
						        						</td>';
				        						}else{
				        							$tabla_3 .= '
						        						<td>
						        							<b> --r </b><br>
						        						</td>';
				        						}
				        					}
	        						}
	        	$tabla_3.='		</tr>';

	        	$tabla_3.='		<tr>
	        						<td class="cyan lighten-5 black-text"><b>Resultado</b></td>';
	        						foreach ($ensayos as $llave => $ensayo) {
	        							$parametro = procesar_registro_fetch('parametro', 'id_parametro', $ensayo->id_parametro);  
				        					if ($parametro[0]->par_estado == 'Activo') {
				        						$ensayo_vs_muestra = procesar_registro_fetch('ensayo_vs_muestra', 'id_ensayo', $ensayo->id_ensayo, 'id_muestra', $certificado->id_muestra_detalle);
				        						if(isset($ensayo_vs_muestra[0]->id_ensayo_vs_muestra)){
				        							if ($ensayo_vs_muestra[0]->resultado_mensaje<>''){
				        								$color="black-text";
							                            if(preg_match("/-MAS-/",evalua_alerta($ensayo->med_valor_min  ,$ensayo->med_valor_max , $ensayo_vs_muestra[0]->resultado_mensaje, $certificado->id_tipo_analisis, $ensayo_vs_muestra[0]->id_ensayo_vs_muestra,2))){//2 para que no genere correos
							                                $color="red-text";  
							                            }
						        						$tabla_3 .= '
							        						<td style=" border-style: dotted;" id="resultado_'.$ensayo_vs_muestra[0]->id_ensayo_vs_muestra.'">
							        							<p class="'.$color.'" ondblclick="editar_campos_redondeo(`resultado_'.$ensayo_vs_muestra[0]->id_ensayo_vs_muestra.'`,`'.$ensayo_vs_muestra[0]->resultado_mensaje.'`,`'.$parametros_aux[$llave]->par_nombre.'`, `frm_resultado_mensaje`, `resultado_mensaje`, `ensayo_vs_muestra` ,`'.$ensayo_vs_muestra[0]->id_ensayo_vs_muestra.'`, `'.($llave+1).'`)"><b>'.$ensayo_vs_muestra[0]->resultado_mensaje.'</b></p>
							        						</td>';
				        							}else{
				        								if ($que_mostrar==2){
				        									$tabla_3 .= '<td><b>Pendiente</b></td>';
				        								}else
				        									$tabla_3 .= '<td>&nbsp</td>';
				        							}
				        						}else{
				        							$tabla_3 .= '
						        						<td>
						        							<b> --r </b><br>
						        						</td>';
				        						}
				        					}
	        						}
	        	$tabla_3.='		</tr>
	        				</tbody>
	        			</table>
	        			<div class="row">
	        				<div class="col s12 l12" id="campo_resultado_redondeo">
	        				</div>
	        			</div>
	        		</div>
	        	</div>';
        }
        if($que_mostrar == 0){
        	$tabla_4 .= '
        		<div class="row">
					<div class="col s12 m6 l6">
						<button class="btn red darken-4"><i class="far fa-file-pdf"></i> Previsualizar documento</button>
					</div>
					<div class="col s12 m6 l6">
						<button class="btn green darken-3" onClick="js_mostrar_detalle(`card-table`, `card-detalle`,``,2,`php_lista_resultados`)">Volver atrás</button>
					</div>
				</div>
        		<input type="submit" name="frm_enviar" value="guardar"/>
                        <input type="hidden" name="frm_id_certificado" id="frm_id_certificado" value="'.$id_certificado.'"/>
                        <input type="hidden" name="frm_id_forma" id="frm_id_forma" value="forma_guardar_resultados"/>
                        <input type="hidden" name="frm_id_responsable" id="frm_id_responsable" value="0"/>
                        <div id="campo_bttn_enviar"></div>
                        <div id="campo_resultados"></div>';
        }else{
	        $tabla_4 .= '
	        	<br>
	        	<div class="row table">
	        		<p class="center-align"></b>Mensajes de resultado</b></p>
	        		<div class="input-field col s12 m6 l6">
					    <select id="frm_mensaje_resultado" name="frm_mensaje_resultado" class="required"
                            onChange="muestra_mensaje(this.value, `mensaje_resultado`)">
					      	<option value="">Seleccione mensaje</option>';
					      	$mensajes = procesar_registro_fetch('mensaje_resultado', 0, 0);
					      	foreach ($mensajes as $key => $mensaje) {
					      		$tabla_4.='<option value="'.$mensaje->id_mensaje.'">'.$mensaje->mensaje_titulo.'</option>';
					      	}
				$tabla_4.='</select>
					    <label><b>Resultado</b></label>
					    <div id="campo_mensaje_resultado"></div>
					</div>
					<div class="input-field col s12 m6 l6">
					    <select id="frm_mensaje_observacion" name="frm_mensaje_observacion" class="required"
                            onChange="muestra_mensaje(this.value, `mensaje`)">
					      	<option value="">Seleccione observacion</option>';
					      	$mensajes = procesar_registro_fetch('mensaje', 0, 0);
					      	foreach ($mensajes as $key => $mensaje) {
					      		$tabla_4.='<option value="'.$mensaje->id_mensaje.'">'.$mensaje->mensaje.'</option>';
					      	}
				$tabla_4.='</select>
					    <label><b>Observaci&oacute;n</b></label>
					    <div id="campo_mensaje"></div>
					</div>
	        	</div>
	        	<br>
	        	<div class="row table">
	        		<div class="input-field col s12 m6 l6">
					    <select id="frm_mensaje_firma" name="frm_mensaje_firma" class="required">
					      	<option value="">Seleccione firma</option>';
					      	$sql_firma = "select cf.id_firma
                    			, cu.nombre n1
                    			, cu.cargo c1
                    			, cu2.nombre n2
                    			, cu2.cargo c2 
                    			, cf.id_firma_1
                    			, cf.id_firma_2
                    		from cms_firma cf inner join cms_users cu on cf.id_firma_1=cu.id 
                    		INNER JOIN cms_users cu2 on cf.id_firma_2=cu2.id 
                    		where cf.estado='Activo'";
                    		$firmas = $db->query($sql_firma)->getResult();
                    		foreach ($firmas as $key => $firma) {
                    			$tabla_4.='<option value="'.$firma->id_firma.'">'.$firma->n1.' ('.$firma->c1.') -/- '.$firma->n2.' ('.$firma->c2.')</option>';
                    		}
			$tabla_4 .='</select>
					    <label><b>Firmas</b></label>
					    <p>
					      	<label>
					        	<input type="checkbox" value="1" name="frm_firma_preliminar" id="frm_firma_preliminar" checked />
					        	<span><b>Con firma digital al previsualizar</b></span>
					      	</label>
					    </p>
					</div>
					<div class="input-field col s12 m6 l6">
					    <select id="frm_form_valo" name="frm_form_valo">
					      	<option value="0" selected>Predeterminado</option>
	                        <option value="1">Sin Decimales</option>
	                        <option value="2">Con un decimal</option>
	                        <option value="3">Con dos decimales</option>
					    </select>
					    <label><b>Formato de presentaci&oacute;n de resultados</b></label>
					</div>
	        	</div>
	        	</div class="row table">
	        		<div class="input-field col s12 l12">
	        			<p><label>
					        <input class="with-gap" type="radio" name="frm_plantilla" id="frm_plantilla" value="1" checked/>
					        <span>Plantilla estandar</span>
					    </label></p>
					    <p><label>
					        <input class="with-gap" type="radio" name="frm_plantilla" id="frm_plantilla" value="11"/>
					        <span>Plantilla estandar (2 paginas)</span>
					    </label></p>
					    <p><label>
					        <input class="with-gap" type="radio" name="frm_plantilla" id="frm_plantilla" value="111"/>
					        <span>Plantilla estandar (2 paginas v2)</span>
					    </label></p>
					    <p><label>
					        <input class="with-gap" type="radio" name="frm_plantilla" id="frm_plantilla" value="2"/>
					        <span>Plantilla Frotis</span>
					    </label></p>
					    <p><label>
					        <input class="with-gap" type="radio" name="frm_plantilla" id="frm_plantilla" value="3"/>
					        <span>Plantilla Mixta MA y FA (1 pagina)</span>
					    </label></p>
					    <p><label>
					        <input class="with-gap" type="radio" name="frm_plantilla" id="frm_plantilla" value="4"/>
					        <span>Plantilla Mixta MA y FA (2 paginas)</span>
					    </label></p>
					    <p><label>
					        <input class="with-gap" type="radio" name="frm_plantilla" id="frm_plantilla" value="5"/>
					        <span>Plantilla Esterilidad</span>
					    </label></p>
					</div>
	        	</div>

	        	<hr>';
	        	if($que_mostrar==2)
                    $tabla_4 .= '  <input type="hidden" name="frm_id_procedencia" id="frm_id_procedencia" value="1"/><!--1. para preinformes-->';
            	else
                    $tabla_4 .= '  <input type="hidden" name="frm_id_procedencia" id="frm_id_procedencia" value="2"/><!--2. para informes-->';
	        	$tabla_4 .= ' 
				<div class="row">
					<div class="col s12 m6 l6">
						<button class="btn red darken-4"  onClick="js_enviar()"><i class="far fa-file-pdf"></i> Previsualizar documento</button>
						<small id="campo_bttn_enviar"></small>
						<input type="hidden" name="frm_id_certificado" id="frm_id_certificado" value="'.$id_certificado.'"/>
	                    <input type="hidden" name="frm_id_forma" id="frm_id_forma" value="forma_muestra_preinforme"/>
	                    <input type="hidden" name="frm_id_responsable" id="frm_id_responsable" value="0"/>
	                    <input type="hidden" name="funcion" value="" id="funcion"/>
					</div>
					<div class="col s12 m6 l6">
						<button class="btn green darken-3" onClick="js_mostrar_detalle(`card-table`, `card-detalle`)">Volver atrás</button>
					</div>
				</div>
	        ';
        }
        $salida = $tabla_1.'<hr>'.$tabla_2.'<hr>'.$tabla_3.'<hr>'.$tabla_4;
        return $salida;
	}
	function cambiar_campos($type, $campo_salida, $valor, $nombre_campo_frm, $nombre_campo_bd, $tabla_update, $id_operacion){

		if($valor == '-xxx-'){//por si el valor es vacio no guarde la bandera
       		$valor='';
    	}
    	if($type == 'date'){
    		$valor = $valor.' '.date('H:i:s', strtotime("now"));
			//$valor = utf8_decode($valor);
    	}

    	if($tabla_update == 'muestreo'){
        	$texto = "update muestreo set
                    	$nombre_campo_bd = '$valor'
                    	where id_muestreo = $id_operacion";
    	}elseif($tabla_update == 'certificacion'){
        	$texto = "update certificacion set
                    	$nombre_campo_bd = '$valor'
                    	where certificado_nro = $id_operacion";
    	}elseif($tabla_update == 'muestreo_detalle'){
        	$texto = "update muestreo_detalle set
                    	$nombre_campo_bd = '$valor'
                    	where id_muestra_detalle = $id_operacion";
    	}elseif($tabla_update == 'ensayo_vs_muestra'){
    		$texto = "update ensayo_vs_muestra set
                		$nombre_campo_bd = '$valor'
                		where id_ensayo_vs_muestra = $id_operacion";
		}

    	if(empty($valor)){
        	if($valor<>0)
         		$valor='-xxx-';
    	}
    	$db = \Config\Database::connect();
    	$result = $db->query($texto);
	    //formateamos las salida para regla
	     if (!is_numeric($campo_salida)) {
	    	$type == 'date' ? date('Y-m-d', strtotime($valor)):$valor;
	     	$salida = '<p ondblclick="editar_campos(`'.$type.'`,`'.$campo_salida.'`,`'.$valor.'`, `'.$nombre_campo_frm.'`, `'.$nombre_campo_bd.'`, `'.$tabla_update.'` , `'.$id_operacion.'`)">'.$valor.'</p>';
    	}else{
        	$salida ="<spam style='color:blue'>Regla aplicada </spam>";
            $campo_salida = 'campo_regla_'.$campo_salida;
    	}
    	return [$salida, $campo_salida];
	}

	function muestra_mensaje($id_mensaje, $tabla){
		if ($id_mensaje){
			$mensaje = procesar_registro_fetch($tabla, 'id_mensaje', $id_mensaje);
            // $sql_mensaje = "select * from $tabla where id_mensaje = $id_mensaje";

            // $query_mensaje = mysql_query($sql_mensaje) or die ('error '.  mysql_error().$sql_mensaje);
            // $fila_mensaje = mysql_fetch_object($query_mensaje);

            if($tabla == 'mensaje_resultado'){
                $salida = $mensaje[0]->mensaje_titulo;
            }else{
                $salida = $mensaje[0]->mensaje_detalle;
            }
	    }else{
	        $salida = "";
	    }
	  	$respuesta = ['tabla' => $tabla, 'mensaje' => $salida];
    	return $respuesta;

    
    // $respuesta->assign("campo_$tabla","innerHTML", utf8_encode($salida));
	}
	function presentar_preinforme($form){
		$db = \Config\Database::connect();
		$fila_existe = procesar_registro_fetch('certificacion_vs_mensaje', 'id_certificacion', $form['frm_id_certificado'], 'id_mensaje_tipo', $form['frm_id_procedencia']);
		if (!empty($fila_existe[0])){//existe actualiza
	        $sql = "update certificacion_vs_mensaje set
	                            id_mensaje_resultado='".$form['frm_mensaje_resultado']."', 
	                            id_mensaje_comentario='".$form['frm_mensaje_observacion']."',
	                            id_firma = ".$form['frm_mensaje_firma'].",
	                            id_plantilla = ".$form['frm_plantilla']." ,
	                            form_valo =  ".$form['frm_form_valo']."
	                            where id_certificacion=".$form['frm_id_certificado']." and id_mensaje_tipo=".$form['frm_id_procedencia'];

	    }else{//inserta
	        $sql = "insert into certificacion_vs_mensaje (id_certificacion, id_mensaje_resultado, id_mensaje_comentario, id_mensaje_tipo,
	                            id_firma, id_plantilla, form_valo)
	                            values
			(".$form['frm_id_certificado'].",'".$form['frm_mensaje_resultado']."', '".$form['frm_mensaje_observacion']."', '".$form['frm_id_procedencia']."',
	                    ".$form['frm_mensaje_firma'].",".$form['frm_plantilla'].",".$form['frm_form_valo'].")";

	    }
	    if ($db->simpleQuery($sql)) {
		    $salida .= "<br>Operacion ok 11111";
		}
		$certificado = procesar_registro_fetch('certificacion', 'certificado_nro', $form['frm_id_certificado']);
		$certificado = $certificado[0];
		//formateo de muestreo
        $sql = "select * from muestreo where id_muestreo=$certificado->id_muestreo  group by id_muestreo";
        $muestreo = $db->query($sql)->getResult();
        $muestreo = $muestreo[0];
	    // return $muestreo;
    	//formateo de cliente
        $cliente = procesar_registro_fetch('usuario ', 'id', $muestreo->id_cliente);
        if($form['frm_id_procedencia'] == 1){//preinformes
            $aux_mensaje='PRELIMINAR';
            if($certificado->cer_fecha_preinforme == '0000-00-00 00:00:00') $aux_fecha_informe=date("Y-m-d H:i:s"); // True
            else $aux_fecha_informe=$certificado->cer_fecha_preinforme;
        }else{
            $aux_mensaje='REPORTE DE ENSAYO 1';
            if($certificado->cer_fecha_informe == '0000-00-00 00:00:00') $aux_fecha_informe=date("Y-m-d H:i:s"); // True
            else $aux_fecha_informe=$certificado->cer_fecha_informe;
        }
         //formatemos el subtitulo de la empresa
        if($muestreo->mue_subtitulo){
            //$fila_muestreo->mue_subtitulo = str_replace("Ã±", "ñ", $fila_muestreo->mue_subtitulo);
            $muestreo->mue_subtitulo = formatea_tildes($muestreo->mue_subtitulo);
            $muestreo->mue_subtitulo = ' - '.$muestreo->mue_subtitulo;
            
        }
        //recortar fecha
        $certificado->cer_fecha_analisis = recortar_fecha($certificado->cer_fecha_analisis, 1);

        //BUSCAMOS EL MENSAJE DE TIPO DE MUESTERO
        //SE TOMARA EL DEL PRIMER REGISTRO YA QUE PUEDE TENER ASOCIADO VARIOS PRODUCTOS
        $fila_detalle_para_tipo_muestreo = procesar_registro_fetch('muestreo_detalle', 'id_muestra_detalle', $certificado->id_muestreo_detalle);
        $fila_detalle_para_tipo_muestreo = $fila_detalle_para_tipo_muestreo[0];

        //formatemos la fecha de analisis
        $fecha_analisis = recortar_fecha($muestreo->mue_fecha_muestreo,1);
        return [
	    	'db' => $db,
	    	'certificado' => $certificado,
	    	'cliente' => $cliente[0],
	    	'muestreo' => $muestreo,
	    	'aux_fecha_informe' => $aux_fecha_informe,
	    	'aux_mensaje' => $aux_mensaje,
	    	'fecha_analisis' => $fecha_analisis,
	    	'fila_detalle_para_tipo_muestreo' => $fila_detalle_para_tipo_muestreo,
	    	'plantilla' => $form['frm_plantilla'],
	    	'form_entrada' => $form
	    ];
        
	}
?>