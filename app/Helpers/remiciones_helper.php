<?php
use App\Models\Producto;
use App\Models\Parametros;
use App\Models\Ensayo;
	function muestra_tabla($producto){
		$data = new Producto();
		$producto = $data
			->join('norma', 'producto.id_norma = norma.id_norma')
			->where(['pro_nombre' => $producto])->get()->getResult();
		$tabla = '
			<div class="tabla-productos">
				<hr>
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
						<table class="striped centered">
							<thead>
								<tr>';
									$ensayo = procesar_registro_fetch('ensayo', 'id_producto', $producto[0]->id_producto);
									foreach($ensayo as $key => $value){
										$parametro = procesar_registro_fetch('parametro', 'id_parametro', $value->id_parametro);
										if($parametro[0]->par_estado == 'Inactivo')
											continue;
		                                $tabla .='<th><b>'.$parametro[0]->par_nombre.'</b></th>';
									}

					$tabla 	.=	'</tr>
							</thead>
							<tbody>
								<tr>';
								foreach($ensayo as $key => $value){
									$parametro = procesar_registro_fetch('parametro', 'id_parametro', $value->id_parametro);
									if($parametro[0]->par_estado == "Inactivo")
	                                    continue;
	                                $tabla .= '<td>'.$value->med_valor_min.' - '.$value->med_valor_max.'</td>';
								}
				$tabla .= 	'	</tr>
								<tr>';

								$aux_cols = 0;
								foreach($ensayo as $value){
									$parametro = procesar_registro_fetch('parametro', 'id_parametro', $value->id_parametro);
									if($parametro[0]->par_estado == "Inactivo")
	                                    continue;
	                                $checked = "checked";
	                                $tabla .='<td  class="action">
	                                		<label>
		                                		<input type="checkbox" name="frm_chk_'.$parametro[0]->id_ensayo.'" id="frm_chk_'.$parametro[0]->id_ensayo.'" '.$checked.'/>
		                                		<span></span>
	                                		</label>
	                                	</td>';
                        			$aux_cols++;
								}
				$mensaje = "Agregar a lista";
               	$inputIdMuestraDetalle = '';

				$tabla .=	'	</tr>
								<tr><td colspan="'.$aux_cols.'">&nbsp;</td></tr>
								<tr>
			                        <td colspan="'.$aux_cols.'" class="action">
			                        	<b>Unidad de Medida</b><br>
			                        	<label>
			                        		<input  type="radio" id="frm_unidad_parametro" name="frm_unidad_parametro" value="solida" checked>
			                        		<span>S&oacute;lidas</span>
	                                	</label>
			                        	<label>
			                        		<input  type="radio" id="frm_unidad_parametro" name="frm_unidad_parametro" value="liquida">
			                        		<span>Liquidas</span>
	                                	</label>
			                        	<br><br>
			                        </td>
			                    </tr>
			                    <tr>
			                        <td colspan="'.$aux_cols.'" class="action">
			                        	<label>
			                        		<input type="checkbox" value="1" name="frm_bandera_certificado" id="frm_bandera_certificado" checked>
			                        		<span>Un certificado</span>
	                                	</label>
	                                </td>
			                    </tr>
			                    <tr>
					                <td colspan="'.$aux_cols.'" class="action"><div id="campo_botton_agregar">
					                	<button class="btn gradient-45deg-purple-deep-orange border-round">'.$mensaje.'</button>
					                	<input type="hidden" name="frm_id_forma" id="frm_id_forma" value="frm_form_muestra"/>
					                	'.$inputIdMuestraDetalle.'
					                </td>
					            </tr>
							</tbody>
						</table>
					</tbody>
				</table>
			</div>
		';
	    return $tabla;
	}