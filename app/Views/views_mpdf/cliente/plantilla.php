<body style="background: rgb(51, 51, 51); display: flex; justify-content: center;">
	<div style="width: 800px;background: white; padding: 30px 20px;">
		<table style="width: 100%;">
			<thead>
				<tr class="amc-border amc-centrado">
					<th>
						<img src="assets/img/logo_1.png" width="150" height="40">
					</th>
					<th>
						<div id="amc-header">
							<strong>Reporte de Ensayo</strong>
						</div>
					</th>
					<th>
						<div id="amc-header">
							<strong>AMC - EI -FT - 01 <br> Versi&oacute;n 03</strong>
						</div>
					</th>
				</tr>
				<tr class="amc-centrado">
					<td colspan="3">
						<div id="amc-header" >
							<strong><?= $aux_mensaje ?> No. AMC - <?= $certificado->certificado_nro ?> </strong>
						</div>
					</td>
				</tr>
			</thead>
			<tbody>
				<!-----   area de cliente ---->
				<tr>
					<td colspan="3">
						<table width="100%" >
							<tr>
								<td class="vertical-align-top" style="width: 50%;">
									<table>
										<tr>
											<td><b>CLIENTE:</b></td>
											<td><?= $cliente->name ?> </td>
										</tr>
										<tr>
											<td><b>DIRIGIDO A:</b></td>
											<td><?= $cliente->use_cargo.' '.strip_tags($cliente->use_nombre_encargado) ?></td>
										</tr>
										<tr>
											<td><b>DIRECCIÓN:</b></td>
											<td><?= $cliente->use_direccion ?></td>
										</tr>
										<tr>
											<td><b>TELÉFAX:</b></td>
											<td><?= $cliente->use_telefono.' - '.$cliente->use_fax ?></td>                        
										</tr>
										<tr>
											<td><b>MAIL:</b></td>
											<td><?= $cliente->email ?></td>
										</tr>
										
									</table>
								</td>
								<td class="vertical-align-top" style="width: 50%;">
									<table>
										<tr>
											<td><b>SUCURSAL:</b></td>
											<td><?= $muestreo->mue_subtitulo ?></td>
										</tr>
										<tr>
											<td><b>RESPONSABLE DE TOMA DE MUESTRA:</b></td>
											<td><?= $muestreo->mue_entrega_muestra ?></td>
										</tr> 
										<tr>
											<td><b>FECHA TOMA DE MUESTRA/HORA:</b></td>
											<td><?= $muestreo->mue_fecha_muestreo ?></td>
										</tr> 
										<tr>
											<td><b>FECHA DE RECEPCIÓN</b></td>
											<td><?= $muestreo->mue_fecha_recepcion ?></td>
										</tr>
										<tr>
											<td><b>FECHA ANÁLISIS:</b></td>
											<td><?= $fecha_analisis ?></td>
										</tr>
										<tr>
											<td><b>FECHA DE INFORME:</b></td>
											<td><?= $aux_fecha_informe ?></td>
										</tr>
										<tr>
											<td><b>MÉTODO DE TOMA DE MUESTRA:</b></td>
											<td><?= $detalle_para_tipo_muestreo->mue_tipo_muestreo ?></td>
										</tr>                       
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<?php 
					$aux_incertidumbre = 0;//solo aplica para plantilla estandar
					$array_regla = array();
					//formateo deacuerdo a la plantilla
				?>
				<?php if($frm_plantilla==1 || $frm_plantilla==11 || $frm_plantilla==111): ?><!-- PLANTILLA STANDAR -->
					
					<?php
						$body = view('views_mpdf/cliente/plantillas/body_estandar', [
							'certificado' => $certificado,
							'frm_form_valo' => $frm_form_valo
						]);
					?>
					
				<?php elseif($frm_plantilla == 2): ?><!-- PLANTILLA FROTIS -->
					<?php
						$body = view('views_mpdf/cliente/plantillas/body_frotis', [
							'certificado' => $certificado,
							'frm_form_valo' => $frm_form_valo
						]);
					?>
				<?php elseif($frm_plantilla == 5): ?><!-- PLANTILLA Esterilidad -->
					
					<?php
						$body = view('views_mpdf/cliente/plantillas/body_esterilidad', [
							'certificado' => $certificado,
							'frm_form_valo' => $frm_form_valo,
							'fecha_analisis' => $fecha_analisis
						]);
					?>

				<?php endif ?>
				<?= $body ?>
				<?php
					if($frm_mensaje_resultado == 0){
						$mensajes=  procesar_registro_fetch('certificacion_vs_mensaje', 'id_certificacion', $certificado->certificado_nro, 'id_mensaje_tipo', $tipo_mensajes);
						$id_mensaje_resultado = $mensajes[0]->id_mensaje_resultado;
						$id_mensaje_observacion = $mensajes[0]->id_mensaje_comentario;
					}else{		
						$id_mensaje_resultado = $frm_mensaje_resultado;
						$id_mensaje_observacion = $frm_mensaje_observacion;
					}

					$fila_resultado=procesar_registro_fetch('mensaje_resultado', 'id_mensaje', $id_mensaje_resultado);
					$fila_resultado_observacion=procesar_registro_fetch('mensaje', 'id_mensaje', $id_mensaje_observacion);

					// firma de sistema
					$firma	 = procesar_registro_fetch('cms_firma', 'id_firma', $frm_mensaje_firma);
					$firma1 = procesar_registro_fetch('cms_users', 'id', $firma[0]->id_firma_1);
					$firma2 = procesar_registro_fetch('cms_users', 'id', $firma[0]->id_firma_2);

					$aux_nombre1   = $firma1[0]->nombre;
					$aux_cargo1    = $firma1[0]->cargo;
					$aux_firma     = $firma1[0]->firma;
					$aux_nombre    = $firma2[0]->nombre;
					$aux_cargo     = $firma2[0]->cargo;
					$texto_incertidumbre = "";
					if($aux_incertidumbre == 1){
						$texto_incertidumbre = "<br><b> µ </b> = incertidumbre expandida al valor reportado con un factor de cobertura de k=2, para un intervalo de confianza de aproximadamente el 95%";
					}
				?>
				<!----- Area de Resultados y observaciones ---->

				<tr class="amc-centrado">
					<td colspan="3" >
						<div id="amc-header2">
							<b><strong><?= $fila_resultado[0]->mensaje_titulo ?></strong></b><br>
						</div>
					</td>
				</tr>
				<tr class="amc-border2">
					<td colspan="3">
						<strong>Observaciones:</strong>
						<p style="font-style: italic; font-size: 10px">
						<?= $texto_incertidumbre; ?>
						
						<?php foreach ( $array_regla as $key => $value ): ?>
							<br><b><?= ($key+1) ?></b>= <?= $value ?>
						<?php endforeach ?>
						</p>
						<br>
						<p style="font-style: italic; font-size: 10px">
							<?= $fila_resultado_observacion->mensaje_detalle ?>
							* Confirme la validez de este documento ingresando a www.amc-laboratorios.com y el codigo <?= $certificado->clave_documento_pre ?>
						</p>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<br><strong>AMC Análisis de Colombia Ltda </strong>
						<table width="100%" class="firmas">
							<tr>
								<td >
									<!-- <p>assets/img/firmas/<?= $aux_firma ?></p> -->
									<img src="assets/img/firmas/<?= $aux_firma ?>" width="100">
								</td>
								<td >
									&nbsp;
								</td>               
							</tr>
							<tr>
								<td >
								<?= $aux_nombre1 ?> 
									<br><strong><?= $aux_cargo1 ?></strong>
								</td>
								<td >
								<?= $aux_nombre ?>
									<br><strong><?= $aux_cargo ?></strong>
								</td>               
							</tr>
						</table>
					</td>
				</tr>
				<tr  class="amc-centrado">
					<td colspan="3">
						<div id="amc-header2">                        
							<strong> - FIN DE INFORME - </strong><br>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</body>