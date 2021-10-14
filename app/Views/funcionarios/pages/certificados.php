<form id="form-certificados" action="<?= base_url(['funcionario', 'certificados']) ?>" method="POST">
	<div class="row">
		<div class="col s12 l12">
			<b><h5 class="center-align">Resultado para el certificado <?= $id_certificado ?></h5></b>
			<table class="striped">
				<thead>
					<tr>
		            	<th><b>Procedencia</b></th>
		                <th><?= $certificados[0]->name ?></th>
		                <th><b>Muestreo</b></th>
		                <th id="campo_fecha_muestreo"><p class="doble_click" ondblclick="editar_campos('date','campo_fecha_muestreo','<?= date('Y-m-d', strtotime($certificados[0]->mue_fecha_muestreo)) ?>', 'frm_fecha_muestra', 'mue_fecha_muestreo', 'muestreo' , '<?= $certificados[0]->id_muestreo ?>')"><?= $certificados[0]->mue_fecha_muestreo ?></p></th>
		            </tr>
		            <tr>
		           		<th><b>Sub titulo</b></th>
		                <th id="campo_sub_titulo"><p class="doble_click" ondblclick="editar_campos(`text`, `campo_sub_titulo`,`<?= $certificados[0]->mue_subtitulo ?>`, `frm_nombre_empresa_subtitulo`, `mue_subtitulo`, `muestreo` ,`<?= $certificados[0]->id_muestreo ?>`)"><?= $certificados[0]->mue_subtitulo ?></p></th>
		                <th><b>Registro</b></th>
		                <th id="campo_fecha_recepcion"><p class="doble_click" ondblclick="editar_campos(`date`,`campo_fecha_recepcion`,`<?= date('Y-m-d', strtotime($certificados[0]->mue_fecha_recepcion)) ?>`, `frm_fecha_recepcion`, `mue_fecha_recepcion`, `muestreo` ,`<?= $certificados[0]->id_muestreo ?>`)"><?= $certificados[0]->mue_fecha_recepcion ?></p></th>
		            </tr>
		            <tr>
		            	<th><b>Nit</b></th>
		                <th><?= $certificados[0]->id ?></th>
		                <th><b>Analisis</b></th>
		                <th></th>
		            </tr>
		            <tr>
		            	<th><b>&nbsp;</b></th>
		                <th><b>&nbsp;</b></th>
		                <th><b>Elaboracion del Pre-informe</b></th>
		                <th id="campo_fecha_preinforme"><p class="doble_click" ondblclick="editar_campos(`date`,`campo_fecha_preinforme`,`<?= date('Y-m-d', strtotime($certificados[0]->cer_fecha_preinforme)) ?>`, `frm_fecha_preinforme`, `cer_fecha_preinforme`, `certificacion` ,`<?= $id_certificado ?>`)"><?= $certificados[0]->cer_fecha_preinforme ?></p></th>
		            </tr>
		            <tr>
		            	<th><b>&nbsp;</b></th>
		                <th><b>&nbsp;</b></th>
		                <th><b>Elaboracion del informe</b></th>
		                <th id="campo_fecha_informe"><p class="doble_click" ondblclick="editar_campos(`date`,`campo_fecha_informe`,`<?= date('Y-m-d', strtotime($certificados[0]->cer_fecha_informe)) ?>`, `frm_fecha_informe`, `cer_fecha_informe`, `certificacion` ,`<?= $id_certificados ?>`)"><?= $certificados[0]->cer_fecha_informe ?></p></th>
		            </tr>
				</thead>
			</table>
		</div>
	</div>
	<hr>
	<?php foreach ($certificados as $key => $certificado): ?>
		<?php 	
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
	    ?>
	    <!-- // Encabezado -->
		<div class="row">
			<div class="col s12 l12">
				<h5 class="center-align"><b>Muestra <?=  construye_codigo_amc($certificado->id_muestra_detalle)?></b></h5>
				<table class="striped">
					<thead>
						<tr>
			            	<th><b>Procedencia</b></th>
			                <th id="campo_procedencia<?= $certificado->id_muestra_detalle?>"><p class="doble_click" ondblclick="editar_campos(`text`,`campo_procedencia<?= $certificado->id_muestra_detalle?>`,`<?= $certificado->mue_procedencia?>`, `frm_procedencia`, `mue_procedencia`, `muestreo_detalle` ,`<?= $certificado->id_muestra_detalle?>`)"><?= $certificado->mue_procedencia?></p></th>
			                <th><b>Identificaci&oacute;n</b></th>
			                <th id="campo_identificacion<?= $certificado->id_muestra_detalle?>"><p class="doble_click" ondblclick="editar_campos(`text`,`campo_identificacion<?= $certificado->id_muestra_detalle?>`,`<?= $certificado->mue_identificacion?>`, `frm_identificacion`, `mue_identificacion`, `muestreo_detalle` ,`<?= $certificado->id_muestra_detalle?>`)"><?= $certificado->mue_identificacion?></p></th>
			           		<th><b>No. lote</b></th>
			                <th id="campo_lote<?= $certificado->id_muestra_detalle?>"><p class="doble_click" ondblclick="editar_campos(`text`,`campo_lote<?= $certificado->id_muestra_detalle?>`,`<?= $certificado->mue_lote?>`, `frm_lote`, `mue_lote`, `muestreo_detalle` ,`<?= $certificado->id_muestra_detalle?>`)"><?= $certificado->mue_lote?></p></th>
			            </tr>
			            <tr>
			                <th><b>Fecha Producci&oacute;n</b></th>
			                <th id="campo_fecha_produccion<?= $certificado->id_muestra_detalle?>"><p class="doble_click" ondblclick="editar_campos(`date`,`campo_fecha_produccion<?= $certificado->id_muestra_detalle?>`,`<?= date('Y-m-d', strtotime($certificado->mue_fecha_produccion))?>`, `frm_fecha_produccion`, `mue_fecha_produccion`, `muestreo_detalle` ,`<?= $certificado->id_muestra_detalle?>`)"><?= $certificado->mue_fecha_produccion?></p></th>
			            	<th><b>Fecha vencimiento</b></th>
			                <th id="campo_fecha_vencimiento<?= $certificado->id_muestra_detalle?>"><p class="doble_click" ondblclick="editar_campos(`date`,`campo_fecha_vencimiento<?= $certificado->id_muestra_detalle?>`,`<?= date('Y-m-d', strtotime($certificado->mue_fecha_vencimiento))?>`, `frm_fecha_vencimiento`, `mue_fecha_vencimiento`, `muestreo_detalle` ,`<?= $certificado->id_muestra_detalle?>`)"><?= $certificado->mue_fecha_vencimiento?></p></th>
			                <th><b>Temp. Muestreo</b></th>
			                <th id="campo_temperatura_muestreo<?= $certificado->id_muestra_detalle?>"><p class="doble_click" ondblclick="editar_campos(`text`,`campo_temperatura_muestreo<?= $certificado->id_muestra_detalle?>`,`<?= $certificado->mue_temperatura_muestreo?>`, `frm_temperatura_muestreo`, `mue_temperatura_muestreo`, `muestreo_detalle` ,`<?= $certificado->id_muestra_detalle?>`)"><?= $certificado->mue_temperatura_muestreo?></p></th>
			            </tr>
			            <tr>
			                <th><b>Temp. recepci&oacute;n</b></th>
			                <th id="campo_temperatura_laboratorio<?= $certificado->id_muestra_detalle?>"><p class="doble_click" ondblclick="editar_campos(`text`,`campo_temperatura_laboratorio<?= $certificado->id_muestra_detalle?>`,`<?= $certificado->mue_temperatura_laboratorio?>`, `frm_temperatura_laboratorio`, `mue_temperatura_laboratorio`, `muestreo_detalle` ,`<?= $certificado->id_muestra_detalle?>`)"><?= $certificado->mue_temperatura_laboratorio?></p></th>
			            	<th><b>Condiciones de recibido</b></th>
			                <th id="campo_condiciones_recibe<?= $certificado->id_muestra_detalle?>"><p class="doble_click" ondblclick="editar_campos(`text`,`campo_condiciones_recibe<?= $certificado->id_muestra_detalle?>`,`<?= $certificado->mue_condiciones_recibe?>`, `frm_condiciones_recibe`, `mue_condiciones_recibe`, `muestreo_detalle` ,`<?= $certificado->id_muestra_detalle?>`)"><?= $certificado->mue_condiciones_recibe?></p></th>
			                <th><b>Cantidad</b></th>
			                <th id="campo_cantidad<?= $certificado->id_muestra_detalle?>"><p class="doble_click" ondblclick="editar_campos(`text`,`campo_cantidad<?= $certificado->id_muestra_detalle?>`,`<?= $certificado->mue_cantidad?>`, `frm_cantidad`, `mue_cantidad`, `muestreo_detalle` ,`<?= $certificado->id_muestra_detalle?>`)"><?= $certificado->mue_cantidad?></p></th>
			            </tr>
			            <tr>
			                <th><b>An&aacute;lisis solicitado</b></th>
			                <th colspan="2"><?= $certificado->mue_nombre?></th>
			            	<th><b>Producto</b></th>
			                <th colspan="2"><?= $certificado->pro_nombre?></th>
			            </tr>
					</thead>
				</table>
			</div>
		</div>

		<!-- Parametros -->
		<?php
			$ensayos = procesar_registro_fetch('ensayo', 'id_producto', $certificado->id_producto);
	        $parametros_aux = [];
	    ?>
	    <div class="row">
	    	<?php $llaves_ensayo = [] ?>
			<div class="col s12 l12">
				<div class="div_parametros">
					<table class="striped centered">
						<thead>
							<tr>
								<th class="black-text"><b>Titulo</b></th>
								<?php foreach ($ensayos as $key => $ensayo): ?>
									<?php $parametro = procesar_registro_fetch('parametro', 'id_parametro', $ensayo->id_parametro); ?>
									<?php if ($parametro[0]->par_estado == 'Activo'): ?>
										<?php $ensayo_vs_muestra = procesar_registro_fetch('ensayo_vs_muestra', 'id_ensayo', $ensayo->id_ensayo, 'id_muestra', $certificado->id_muestra_detalle); ?>
		        						<?php if (!empty($ensayo_vs_muestra[0])): ?>
											<?php
												$llaves_ensayo[$key] = 1;
												$parametros_aux[$key] = $parametro[0];
												$tecnica = procesar_registro_fetch('tecnica', 'id_tecnica', $parametro[0]->id_tecnica);
											?>
											<th class="black-text" style="min-width: 130px">
												<b><?= $parametro[0]->par_nombre ?></b>
												<br>
												<small class="grey-text text-darken-4"><?= $tecnica[0]->nor_nombre ?></small>
												<br>
												<small class="green-text text-darken-4"><b><?= $parametro[0]->par_estado ?></b></small>
											</th>
										<?php endif ?>
									<?php endif ?>
								<?php endforeach ?>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><b>Rango</b></td>
								<?php foreach ($ensayos as $key => $ensayo): ?>
			        					<?php if (!empty($llaves_ensayo[$key])): ?>
											<?php $parametro = procesar_registro_fetch('parametro', 'id_parametro', $ensayo->id_parametro);  ?>
											<?php if ($parametro[0]->par_estado == 'Activo'): ?>
												<td>
				        							<b><?= $ensayo->med_valor_min.' - '.$ensayo->med_valor_max ?></b>
				        						</td>
											<?php endif ?>
										<?php endif ?>
								<?php endforeach ?>
							</tr>
							<tr>
			        			<td><b>Unidades</b></td>
			        			<?php foreach ($ensayos as $key => $ensayo): ?>
			        					<?php if (!empty($llaves_ensayo[$key])): ?>
					        				<?php $parametro = procesar_registro_fetch('parametro', 'id_parametro', $ensayo->id_parametro);  ?>
					        				<?php if ($parametro[0]->par_estado == 'Activo'): ?>
					        					<?php $aux = 'No aplica'; ?>
					        					<?php if ($certificado->mue_unidad_medida): ?>
					        						<?php $aux = ($certificado->mue_unidad_medida == 'solida')?$parametro[0]->unidad_solida:$parametro[0]->unidad_liquida ?>
					        						<?php if (!trim($aux)): ?>
					        							<?php $aux= ($certificado->mue_unidad_medida == 'solida')?'gr':'ml'; ?>
					        						<?php endif ?>
					        					<?php endif ?>
					        					<?php $aux_incertidumbre = ($parametro[0]->incertidumbre) ? $parametro[0]->incertidumbre : 'No aplica'; ?>
					        					<td><b><?= $aux ?></b></td>
					        				<?php endif ?>
					        			<?php endif ?>
			        			<?php endforeach ?>
			        		</tr>
			        		<tr>
			        			<td><b>μ</b></td>
			        			<?php foreach ($ensayos as $key => $ensayo): ?>
			        				<?php if (!empty($llaves_ensayo[$key])): ?>
				        				<?php $parametro = procesar_registro_fetch('parametro', 'id_parametro', $ensayo->id_parametro); ?>
				        				<?php if ($parametro[0]->par_estado == 'Activo'): ?>
			        						<?php $aux = 'No aplica'; ?>
			        						<?php if($certificado->mue_unidad_medida): ?>
					                             <?php $aux = ($certificado->mue_unidad_medida == 'solida')?$parametro[0]->unidad_solida:$parametro[0]->unidad_liquida; ?>
					                             <!-- // si es vacio el campo se mostrara la unidad de medida -->
					                             <?php if(!trim($aux)): ?>
					                                <?php $aux= ($certificado->mue_unidad_medida == 'solida')?'gr':'ml'; ?>
					                             <?php endif ?>
					                         <?php endif ?>
					                        <?php $aux_incertidumbre = ($parametro[0]->incertidumbre) ? $parametro[0]->incertidumbre : 'No aplica'; ?>
				        					<td><b><?= $aux_incertidumbre ?></b></td>
			        					<?php endif ?>
			        				<?php endif ?>
			        			<?php endforeach ?>
			        		</tr>
			        		<tr>
			        			<td><b>Regla</b></td>
			        			<?php foreach ($ensayos as $key => $ensayo): ?>
			        				<?php if (!empty($llaves_ensayo[$key])): ?>
				        				<?php $parametro = procesar_registro_fetch('parametro', 'id_parametro', $ensayo->id_parametro); ?>
				        				<?php if ($parametro[0]->par_estado == 'Activo'): ?>
				        					<?php
				        						$ensayo_vs_muestra = procesar_registro_fetch('ensayo_vs_muestra', 'id_ensayo', $ensayo->id_ensayo, 'id_muestra', $certificado->id_muestra_detalle);
				        						$aux_id_ensayo_vs_muestra = $ensayo_vs_muestra[0]->id_ensayo_vs_muestra;
			        							$aux_id_regla = $ensayo_vs_muestra[0]->id_regla;
				        					?>
				        					<td>
			        							<div class="input-field col s12">
												    <select name="frm_regla_<?= $aux_id_ensayo_vs_muestra ?>"
												    	onChange="cambiar_campos(`text`,<?= $aux_id_ensayo_vs_muestra ?>,this.value,
												    		 `frm_regla_<?= $aux_id_ensayo_vs_muestra ?>`, `id_regla`, `ensayo_vs_muestra`, `<?= $aux_id_ensayo_vs_muestra ?>` )">
												      	<option value="0">No aplica</option>
												      	<?php $reglas = procesar_registro_fetch('regla' ,'estado', 'Activa'); ?>
												      	<?php foreach ($reglas as $key => $regla): ?>
												      		<?php $aux_checked = $regla->id_regla == $aux_id_regla ? "selected":''; ?>
													        <option value="<?= $regla->id_regla ?>" <?= $aux_checked ?> > <?= $regla->nombre ?></option>
												      	<?php endforeach ?>
												  	</select>
												  	<small id="campo_regla_<?= $aux_id_ensayo_vs_muestra ?>"></small>
												</div>
											</td>
				        				<?php endif ?>
			        				<?php endif ?>
			        			<?php endforeach ?>
			        		</tr>
			        		<tr>
			        			<td><b>Registros</b></td>
			        			<?php foreach ($ensayos as $key => $ensayo): ?>
			        				<?php if (!empty($llaves_ensayo[$key])): ?>
				        				<?php $parametro = procesar_registro_fetch('parametro', 'id_parametro', $ensayo->id_parametro); ?>
				        				<?php if ($parametro[0]->par_estado == 'Activo'): ?>
				        					<?php $ensayo_vs_muestra = procesar_registro_fetch('ensayo_vs_muestra', 'id_ensayo', $ensayo->id_ensayo, 'id_muestra', $certificado->id_muestra_detalle); ?>
				        					<?php if (!empty($ensayo_vs_muestra[0])): ?>
				        						<td>
				        							<b><?= $ensayo_vs_muestra[0]->resultado_analisis ?> &nbsp;</b><br>
			                						<b><?= $ensayo_vs_muestra[0]->resultado_analisis2 ?> &nbsp;</b>
				        						</td>
				        					<?php endif ?>
				        				<?php endif ?>
			        				<?php endif ?>
			        			<?php endforeach ?>
			        		</tr>
			        		<tr>
			        			<td><b>Resultado</b></td>
			        			<?php foreach ($ensayos as $llave => $ensayo): ?>
			        				<?php if (!empty($llaves_ensayo[$llave])): ?>
			        					<?php $parametro = procesar_registro_fetch('parametro', 'id_parametro', $ensayo->id_parametro);  ?>
			        					<?php if ($parametro[0]->par_estado == 'Activo'): ?>
			        						<?php $ensayo_vs_muestra = procesar_registro_fetch('ensayo_vs_muestra', 'id_ensayo', $ensayo->id_ensayo, 'id_muestra', $certificado->id_muestra_detalle); ?>
			        						<?php if(!empty($ensayo_vs_muestra[0])): ?>
			        							<?php if ($ensayo_vs_muestra[0]->resultado_mensaje<>''): ?>
			        								<?php $color="black-text"; ?>
						                            <?php if(preg_match("/-MAS-/",evalua_alerta($ensayo->med_valor_min  ,$ensayo->med_valor_max , $ensayo_vs_muestra[0]->resultado_mensaje, $certificado->id_tipo_analisis, $ensayo_vs_muestra[0]->id_ensayo_vs_muestra,2))): ?><!-- //2 para que no genere correos -->
						                                <?php $color="red-text" ?>
						                            <?php endif ?>
					        						<td style=" border-style: dotted;" id="resultado_<?= $ensayo_vs_muestra[0]->id_ensayo_vs_muestra ?>">
					        							<p class="<?= $color ?>" ondblclick="editar_campos_redondeo(`resultado_<?= $ensayo_vs_muestra[0]->id_ensayo_vs_muestra ?>`,`<?= $ensayo_vs_muestra[0]->resultado_mensaje ?>`,`<?= $parametros_aux[$llave]->par_nombre ?>`, `frm_resultado_mensaje`, `resultado_mensaje`, `ensayo_vs_muestra` ,`<?= $ensayo_vs_muestra[0]->id_ensayo_vs_muestra ?>`, `<?= ($llave+1) ?>`)"><b><?= $ensayo_vs_muestra[0]->resultado_mensaje ?></b></p>
					        						</td>
			        							<?php else: ?>
				        							<?php if($que_mostrar==2): ?>
				        								<td><b>Pendiente</b></td>
				        							<?php else: ?>
				        								<td>&nbsp;</td>
				        							<?php endif ?>
				        						<?php endif ?>
			        						<?php endif ?>
			        					<?php endif ?>
			        				<?php endif ?>
			        			<?php endforeach ?>
			        		</tr>
						</tbody>
					</table>
				</div>
				<div class="row">
					<br>
					<div class="col s12 l12 cyan lighten-5" id="campo_resultado_redondeo">
					</div>
				</div>
			</div>
		</div>
	<?php endforeach ?>
	<hr>
	<?php if($que_mostrar == 0): ?>
			<div class="row">
				<div class="col s12 m6 l6">
					<button class="btn red darken-4"><i class="far fa-file-pdf"></i> Previsualizar documento</button>
				</div>
				<div class="col s12 m6 l6">
					<button class="btn green darken-3" onClick="js_mostrar_detalle(`card-table`, `card-detalle`,``,2,`php_lista_resultados`)">Volver atrás</button>
				</div>
			</div>
			<input type="submit" name="frm_enviar" value="guardar"/>
	        <input type="hidden" name="frm_id_certificado" id="frm_id_certificado" value="<?= $id_certificado ?>"/>
	        <input type="hidden" name="frm_id_forma" id="frm_id_forma" value="forma_guardar_resultados"/>
	        <input type="hidden" name="frm_id_responsable" id="frm_id_responsable" value="0"/>
	        <div id="campo_bttn_enviar"></div>
	        <div id="campo_resultados"></div>
	<?php else: ?>
	    	<br>
	    	<div class="row table">
	    		<p class="center-align"></b>Mensajes de resultado</b></p>
	    		<div class="input-field col s12 m6 l6">
				    <select id="frm_mensaje_resultado" name="frm_mensaje_resultado" class="required"
	                    onChange="muestra_mensaje(this.value, `mensaje_resultado`)">
				      	<option value="">Seleccione mensaje</option>
				      	<?php $mensajes = procesar_registro_fetch('mensaje_resultado', 0, 0); ?>
				      	<?php foreach ($mensajes as $key => $mensaje): ?>
				      		<option value="<?= $mensaje->id_mensaje ?>"><?= $mensaje->mensaje_titulo ?></option>
				      	<?php endforeach ?>
					</select>
				    <label><b>Resultado</b></label>
				    <div id="campo_mensaje_resultado"></div>
				</div>
				<div class="input-field col s12 m6 l6">
				    <select id="frm_mensaje_observacion" name="frm_mensaje_observacion" class="required"
	                    onChange="muestra_mensaje(this.value, `mensaje`)">
				      	<option value="">Seleccione observacion</option>
				      	<?php $mensajes = procesar_registro_fetch('mensaje', 0, 0); ?>
				      	<?php foreach ($mensajes as $key => $mensaje): ?>
				      		<option value="<?= $mensaje->id_mensaje ?>"><?= $mensaje->mensaje ?></option>
				      	<?php endforeach ?>
					</select>
				    <label><b>Observaci&oacute;n</b></label>
				    <div id="campo_mensaje"></div>
				</div>
	    	</div>
	    	<br>
	    	<?php if ($que_mostrar == 0): ?>
		    		<input type="submit" name="frm_enviar" value="guardar"/>
		            <input type="hidden" name="frm_id_certificado" id="frm_id_certificado" value="<?= $id_certificado ?>"/>
		            <input type="hidden" name="frm_id_forma" id="frm_id_forma" value="forma_guardar_resultados"/>
		            <input type="hidden" name="frm_id_responsable" id="frm_id_responsable" value="0"/>
		            <div id="campo_bttn_enviar"></div>
		            <div id="campo_resultados"></div>
	        	</form>
	    	<?php else: ?>
		    	<div class="row table">
		    		<div class="input-field col s12 m6 l6">
					    <select id="frm_mensaje_firma" name="frm_mensaje_firma" class="required">
					      	<option value="">Seleccione firma</option>
					      	<?php $sql_firma = "select cf.id_firma
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
		            		?>
		            		<?php foreach ($firmas as $key => $firma): ?>
		            			<option value="<?= $firma->id_firma ?>"><?= $firma->n1.' ('.$firma->c1.') -/- '.$firma->n2.' ('.$firma->c2 ?>)</option>
		            		<?php endforeach ?>
						</select>
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
		    	<hr>
		    	<?php if($que_mostrar == 2): ?>
		            <input type="hidden" name="frm_id_procedencia" id="frm_id_procedencia" value="2"/><!--1. para preinformes-->
		    	<?php else: ?>
		            <input type="hidden" name="frm_id_procedencia" id="frm_id_procedencia" value="1"/><!--2. para informes-->
		        <?php endif ?>
						<input type="hidden" name="frm_id_certificado" id="frm_id_certificado" value="<?= $id_certificado ?>"/>
		                <input type="hidden" name="frm_id_forma" id="frm_id_forma" value="forma_muestra_preinforme"/>
		                <input type="hidden" name="frm_id_responsable" id="frm_id_responsable" value="0"/>
		                <input type="hidden" name="funcion" value="" id="funcion"/>
		        	</form>
					<div class="row">
						<div class="col s12 m12 l12">
							<button id="guardar" class="btn blue darken-2" onClick="js_enviar(1, <?= $id_certificado ?>)"><i class="far fa-save"></i> Guardar documento</button>
							<small id="campo_bttn_enviar"></small>
							<button id="enviar" class="btn red darken-2" onClick="js_enviar(0, <?= $id_certificado ?>)"><i class="fad fa-file-pdf"></i> Guardar y descargar documento</button>
							<button id="enviar" class="btn red darken-2" onClick="js_enviar(2, <?= $id_certificado ?>)"><i class="far fa-file-pdf"></i> previsualizar documento</button>
							<button class="btn green darken-3" onClick="js_mostrar_detalle(`card-table`, `card-detalle`)">Volver atrás</button>
						</div>
					</div>
	    	<?php endif ?>
	<?php endif ?>