<?php
  	function limpia_campos_frm($titulo_campo_1){
    
	    $titulo_campo_1 = str_replace(" ","_",$titulo_campo_1);
	    $titulo_campo_1 = str_replace("(","",$titulo_campo_1);
	    $titulo_campo_1 = str_replace(")","",$titulo_campo_1);
	    $titulo_campo_1 = str_replace("/","_",$titulo_campo_1);
	    $titulo_campo_1 = str_replace("[","",$titulo_campo_1);
	    $titulo_campo_1 = str_replace("]","",$titulo_campo_1);
	    $titulo_campo_1 = str_replace("-","",$titulo_campo_1);
	    
	    return $titulo_campo_1;
  	}
	function parametros_aguas_fq(){
		$parametros = [
			[101, 'CLORO LIBRE', 'mg/L Cl2'],
			[86, 'pH', 'U'],
			[241, 'Turbiedad', 'UNT'],
			[237, 'COLOR', 'UPC'],
			[102, 'CONDUCTIVIDAD', '(us/cm)'],
			[234, 'ALCALINIDAD TOTAL', 'Concentracion [H2SO4]', 'mL Fenolftaleina', 'mL desplazados mg/L CaCO3'],
			[236, 'DUREZA TOTAL', 'Concentracion [EDTA]', 'mL desplazados EDTA'],
			[235, 'CLORUROS', 'Concentracion [AgNO3]', 'mL Blanco', 'mL desplazados AgNO3'],
			[240, 'SULFATOS', 'NTU 1', 'NTU 2', 'Valor 1', 'Valor 2'],
			[244, 'NITRITOS', 'mg/L NO2'],
			[239, 'HIERRO', 'mg/L Fe'],
			[238, 'Fosfatos', 'mg/L PO4'],
			[242, 'Aluminio', 'mg/L AL'],
			[245, 'NITRATOS', 'mg/L  NO3'],
			[39, 'Peso capsula con solidos','Peso capsula vacia','Peso muestra'],
			[40, 'Peso capsula con solidos','Peso capsula vacia','Peso muestra']
		];
		return $parametros;
	}
	function pinta_parametro_agua($id_parametro, $id_muestra_detalle, $user_rol_id, $titulo_principal, $titulo_campo_1, $titulo_campo_2 = '',  $titulo_campo_3 = '',  $titulo_campo_4 = '' ){
		$fila = fq_tiene_parametro($id_muestra_detalle, $id_parametro);
		$aux_titulo_label = $titulo_principal;
		$aux_titulo_label_1 = $titulo_campo_1;
		$aux_titulo_label_2 = $titulo_campo_2;
		$aux_titulo_label_3 = $titulo_campo_3;
		$aux_titulo_label_4 = $titulo_campo_4;
		$titulo_principal   = limpia_campos_frm($titulo_principal);
	    $titulo_campo_1     = limpia_campos_frm($titulo_campo_1);
	    $titulo_campo_2     = limpia_campos_frm($titulo_campo_2);
	    $titulo_campo_3     = limpia_campos_frm($titulo_campo_3);
	    $titulo_campo_4     = limpia_campos_frm($titulo_campo_4);
		$salida = '';
		$aux_contador = 0;
		if (!empty($fila[0])){
			$result = procesar_registro_fetch('ensa_mues_fq', 'id_ensayo_vs_muestra', $fila[0]->id_ensayo_vs_muestra, 'id_parametro', $id_parametro);
			if($id_parametro == 39 || $id_parametro == 40){
				$salida .= '
					<tr>
						<td>
							<p class="center-align">
                                <b>Solidos totales.</b>
                                <br>
                                <small>( ( Peso capsula con solidos - Peso capsula vacia ) x 1.000.000 ) / Peso muestra</small>
                            </p>
                            <div class="row">
                                <div class="input-field col s12 l4">
                                    <input type="text" name="frm_'.$titulo_principal.'_alicuota"
                                    id="frm_'.$titulo_principal.'_alicuota" value="'.$result[0]->result_1.'"
                                    onblur="js_cambiar_campos(`campo_repuesta_1_'.$fila[0]->id_ensayo_vs_muestra.'`, this.value, `frm_'.$titulo_principal.'_alicuota`, `result_1`, `'.$fila[0]->id_ensayo_vs_muestra.'`, '.$id_parametro.')"
                                                '.disable_frm($result[0]->result_1, session('user')->id).'>
                                    <label for="frm_'.$titulo_principal.'_alicuota">'.$aux_titulo_label.'</label>
                                    <span id="frm_'.$titulo_principal.'_alicuota"></span>
                                </div>
                                <div class="input-field col s12 l4">
                                    <input type="text" name="frm_'.$titulo_principal.'_'.$titulo_campo_1.'"
                                    id="frm_'.$titulo_principal.'_'.$titulo_campo_1.'" value="'.$result[0]->result_2.'"
                                    onblur="js_cambiar_campos(`campo_repuesta_2_'.$fila[0]->id_ensayo_vs_muestra.'`, this.value, `frm_'.$titulo_principal.'_'.$titulo_campo_1.'`, `result_2`, `'.$fila[0]->id_ensayo_vs_muestra.'`, '.$id_parametro.')"
                                                '.disable_frm($result[0]->result_2, session('user')->id).'>
                                    <label for="frm_'.$titulo_principal.'_'.$titulo_campo_1.'">'.$aux_titulo_label_1.'</label>
                                    <span id="frm_'.$titulo_principal.'_'.$titulo_campo_1.'"></span>
                                </div>
                                <div class="input-field col s12 l4">
                                    <input type="text" name="frm_'.$titulo_principal.'_'.$titulo_campo_2.'"
                                    id="frm_'.$titulo_principal.'_'.$titulo_campo_2.'" value="'.$result[0]->result_3.'"
                                    onblur="js_cambiar_campos(`campo_repuesta_3_'.$fila[0]->id_ensayo_vs_muestra.'`, this.value, `frm_'.$titulo_principal.'_'.$titulo_campo_2.'`, `result_3`, `'.$fila[0]->id_ensayo_vs_muestra.'`, '.$id_parametro.')"
                                                '.disable_frm($result[0]->result_3, session('user')->id).'>
                                    <label for="frm_'.$titulo_principal.'_'.$titulo_campo_2.'">'.$aux_titulo_label_2.'</label>
                                    <span id="frm_'.$titulo_principal.'_'.$titulo_campo_2.'"></span>
                                </div>
                            </div>
				';
				$aux_titulo_label = 'Solidos totales.';
			}else{
				$salida .= '
					<tr>
						<td>
							<p class="center-align">
                                <b>'.$aux_titulo_label.'.</b>
                            </p>
                            <div class="row">
                                <div class="input-field col s12 l3">
                                    <input type="text" name="frm_'.$titulo_principal.'_alicuota"
                                    id="frm_'.$titulo_principal.'_alicuota" value="'.$result[0]->result_1.'"
                                    onblur="js_cambiar_campos(`campo_repuesta_1_'.$fila[0]->id_ensayo_vs_muestra.'`, this.value, `frm_'.$titulo_principal.'_alicuota`, `result_1`, `'.$fila[0]->id_ensayo_vs_muestra.'`, '.$id_parametro.')"
                                                '.disable_frm($result[0]->result_1, session('user')->id).'>
                                    <label for="frm_'.$titulo_principal.'_alicuota">Alicuota (ml)</label>
                                    <span id="frm_'.$titulo_principal.'_alicuota"></span>
                                </div>
                                <div class="input-field col s12 l3">
                                    <input type="text" name="frm_'.$titulo_principal.'_'.$titulo_campo_1.'"
                                    id="frm_'.$titulo_principal.'_'.$titulo_campo_1.'" value="'.$result[0]->result_2.'"
                                    onblur="js_cambiar_campos(`campo_repuesta_2_'.$fila[0]->id_ensayo_vs_muestra.'`, this.value, `frm_'.$titulo_principal.'_'.$titulo_campo_1.'`, `result_2`, `'.$fila[0]->id_ensayo_vs_muestra.'`, '.$id_parametro.')"
                                                '.disable_frm($result[0]->result_2, session('user')->id).'>
                                    <label for="frm_'.$titulo_principal.'_'.$titulo_campo_1.'">'.$aux_titulo_label_1.'</label>
                                    <span id="frm_'.$titulo_principal.'_'.$titulo_campo_1.'"></span>
                                </div>
				';
				$aux_contador = 2;
				if($id_parametro == 234 || $id_parametro == 236  || $id_parametro == 235 || $id_parametro == 240) {
					$aux_contador++;
					$salida .= '
									<div class="input-field col s12 l3">
	                                    <input type="text" name="frm_'.$titulo_principal.'_'.$titulo_campo_2.'"
	                                    id="frm_'.$titulo_principal.'_'.$titulo_campo_2.'" value="'.$result[0]->result_3.'"
	                                    onblur="js_cambiar_campos(`campo_repuesta_3_'.$fila[0]->id_ensayo_vs_muestra.'`, this.value, `frm_'.$titulo_principal.'_'.$titulo_campo_2.'`, `result_3`, `'.$fila[0]->id_ensayo_vs_muestra.'`, '.$id_parametro.')"
	                                                '.disable_frm($result[0]->result_3, session('user')->id).'>
	                                    <label for="frm_'.$titulo_principal.'_'.$titulo_campo_2.'">'.$aux_titulo_label_2.'</label>
	                                    <span id="frm_'.$titulo_principal.'_'.$titulo_campo_2.'"></span>
	                                </div>
					';
				}
			}
			if($id_parametro == 234 || $id_parametro == 235 || $id_parametro == 240 ) {
				$aux_contador++;
				$salida .= '
									<div class="input-field col s12 l3">
	                                    <input type="text" name="frm_'.$titulo_principal.'_'.$titulo_campo_3.'"
	                                    id="frm_'.$titulo_principal.'_'.$titulo_campo_3.'" value="'.$result[0]->result_4.'"
	                                    onblur="js_cambiar_campos(`campo_repuesta_4_'.$fila[0]->id_ensayo_vs_muestra.'`, this.value, `frm_'.$titulo_principal.'_'.$titulo_campo_3.'`, `result_4`, `'.$fila[0]->id_ensayo_vs_muestra.'`, '.$id_parametro.')"
	                                                '.disable_frm($result[0]->result_4, session('user')->id).'>
	                                    <label for="frm_'.$titulo_principal.'_'.$titulo_campo_3.'">'.$aux_titulo_label_3.'</label>
	                                    <span id="frm_'.$titulo_principal.'_'.$titulo_campo_3.'"></span>
	                                </div>';
			}
			if($aux_contador == 4){
				$salida .= '</div>
                <div class="row">';
			}
			if($id_parametro == 234 || $id_parametro == 236 || $id_parametro == 235 ) {
				$factores = procesar_registro_fetch('factor_fq', 'estado', 'Activo');
				$salida .= '<div class="input-field col s12 l3">
							    <select 
							    	name="frm_'.$titulo_principal.'_factor"
                                	id="frm_'.$titulo_principal.'_factor"
                                	onchange="js_cambiar_campos(`campo_repuesta_factor_'.$fila[0]->id_ensayo_vs_muestra.'`,this.value, `frm_'.$titulo_principal.'_factor`, `id_factor`, `'.$fila[0]->id_ensayo_vs_muestra.'`, `'.$id_parametro.'`)"
                                	'.disable_frm($result[0]->id_factor, session('user')->usr_rol).'
                                >
							      	<option>Seleccione factor</option>';
				foreach ($factores as $key => $factor) {
					$aux_select = $factor->id_factor == $result[0]->id_factor ? 'selected': '';
					$salida .= '<option value="'.$factor->id_factor.'"'.$aux_select.'>'.$factor->nombre.' | '.$factor->valor.'</option>';
				}
				$salida .= '
						    </select>
						    <label>Factor</label>
						    <span id="frm_'.$titulo_principal.'_factor"></span>
						 </div>';
			}elseif($id_parametro == 240 ) {
				$salida .= '<div class="input-field col s12 l3">
	                            <input type="text" name="frm_'.$titulo_principal.'_'.$titulo_campo_4.'"
	                            id="frm_'.$titulo_principal.'_'.$titulo_campo_4.'" value="'.$result[0]->result_6.'"
	                            onblur="js_cambiar_campos(`campo_repuesta_5_'.$fila[0]->id_ensayo_vs_muestra.'`, this.value, `frm_'.$titulo_principal.'_'.$titulo_campo_4.'`, `result_6`, `'.$fila[0]->id_ensayo_vs_muestra.'`, '.$id_parametro.')"
	                                        '.disable_frm($result[0]->result_6, session('user')->id).'>
	                            <label for="frm_'.$titulo_principal.'_'.$titulo_campo_4.'">'.$aux_titulo_label_4.'</label>
	                            <span id="frm_'.$titulo_principal.'_'.$titulo_campo_4.'"></span>
	                        </div>';
			}
			if($aux_contador != 4) $salida .= '</div><div class="row">';
			if($aux_contador == 4) $aux_col = 3;
			else $aux_col = 4;
			$salida .= '
							<div class="col s12 l'.$aux_col.'">
	                            <p><b>'.$aux_titulo_label.'</b></p>
	                            <b id="campo_repuesta_agua_'.$fila[0]->id_ensayo_vs_muestra.'">'.$result[0]->result_5.'</b>
	                        </div>
	                        <div class="col s12 l'.$aux_col.'">
	                            <p><b>IRCA '.$aux_titulo_label.'</b></p>
	                            <b id="campo_respuesta_irca_'.$fila[0]->id_ensayo_vs_muestra.'">'.$result[0]->result_irca.'</b>
	                        </div>';
	            $equipos = procesar_registro_fetch('equipo_fq', 'estado', 'Activo');
	            $salida .= '
						<div class="input-field col s12 l3">
						    <select
						    	name="frm_'.$titulo_principal.'_equipo"
	                            id="frm_'.$titulo_principal.'_equipo"
	                            onchange="js_cambiar_campos(\'campo_repuesta_equipo_'.$fila[0]->id_ensayo_vs_muestra.'\',this.value, \'frm_'.$titulo_principal.'_equipo\', \'id_equipo\', \''.$fila[0]->id_ensayo_vs_muestra.'\', '.$id_parametro.')"
	                            '.disable_frm($result[0]->id_equipo, session('user')->usr_rol).'
	                            >
						      	<option value="0">Seleccione equipo</option>
						    ';
	            foreach ($equipos as $key => $equipo) {
	            	$aux_select = $equipo->id_equipo == $result[0]->id_equipo ? 'selected':'';
	            	$salida .= '<option value="'.$equipo->id_equipo.'" '.$aux_select.'>'.$equipo->nombre.'</option>';
	            }

			$salida .= '
								</select>
							    <label>Codigo equipo</label>
							    <span id="frm_'.$titulo_principal.'_equipo"></span>
							 </div>
						</div>
					</td>
				</tr>
			';	
		}
		return $salida;
	}
	function comprueba_calcular_resultado_agua($id_ensayo_vs_muestra, $id_parametro){
	    //buscamos si ya estan diligenciado los valores
	    //si hacemos el calculo y guardamos en fq y ensayo_vs_resultado
	    $result_fq = procesar_registro_fetch('ensa_mues_fq', 'id_ensayo_vs_muestra', $id_ensayo_vs_muestra, 'id_parametro', $id_parametro);
	    $result_fq = $result_fq[0];
	    
	    $result_fq->result_1 = str_replace(",",".",$result_fq->result_1);
	    $result_fq->result_2 = str_replace(",",".",$result_fq->result_2);
	    $result_fq->result_3 = str_replace(",",".",$result_fq->result_3);
	    $result_fq->result_4 = str_replace(",",".",$result_fq->result_4);
	    $result_fq->result_6 = str_replace(",",".",$result_fq->result_6);
	    
	    $nombre_campo_frm ="";
	    ///cloro 101
	    if(isset($result_fq->result_2)   && $result_fq->id_equipo && $id_parametro ==101){
	        //$salida="Calcula el cloro";
	        
	        $result =   $result_fq->result_2;
	        $nombre_campo_frm ="resultado_cloro";
	        $nombre_campo_frm_irca="resultado_cloro_irca";
	        //calculamos el IRCA
	        $salida[1].=calcula_irca_parcial($id_ensayo_vs_muestra, $id_parametro, $result);
	        
	    }
	    //pH 86
	    elseif(isset($result_fq->result_2)   && $result_fq->id_equipo && $id_parametro ==86){
	        //$salida="Calcula el pH";
	        
	        $result =   $result_fq->result_2;
	        $nombre_campo_frm ="resultado_pH";
	        $nombre_campo_frm_irca="resultado_pH_irca";
	        //calculamos el IRCA
	        $salida[1].=calcula_irca_parcial($id_ensayo_vs_muestra, $id_parametro, $result);
	        
	    }
	    //turbides 241
	    elseif(isset($result_fq->result_2)  && $result_fq->id_equipo && $id_parametro == 241){
	        //$salida="Calcula el turbiedad";
	        
	        $result =   $result_fq->result_2;
	        $nombre_campo_frm ="resultado_turbiedad";
	        $nombre_campo_frm_irca="resultado_turbiedad_irca";
	        //calculamos el IRCA
	        $salida[1].=calcula_irca_parcial($id_ensayo_vs_muestra, $id_parametro, $result);
	        
	    }
	    //color 237
	    elseif(isset($result_fq->result_2)  && $result_fq->id_equipo && $id_parametro == 237){
	        //$salida="Calcula el color";
	        
	        $result =   $result_fq->result_2;
	        $nombre_campo_frm ="resultado_color";
	        $nombre_campo_frm_irca="resultado_color_irca";
	        //calculamos el IRCA
	        $salida[1].=calcula_irca_parcial($id_ensayo_vs_muestra, $id_parametro, $result);
	        
	    }
	    //conductividad 102
	    elseif(isset($result_fq->result_2) && $result_fq->id_equipo && $id_parametro ==102){
	        //$salida="Calcula conductividad";
	        
	        $result =   $result_fq->result_2;
	        $nombre_campo_frm ="resultado_conductividad";
	        //$nombre_campo_frm_irca="resultado_conductividad_irca";
	        //calculamos el IRCA
	        //$salida[1].=calcula_irca_parcial($id_ensayo_vs_muestra, $id_parametro, $result);
	        
	    }
	    //ALCALINIDAD 234
	    elseif(isset($result_fq->result_1) && isset($result_fq->result_2) && isset($result_fq->result_3)  && $result_fq->id_factor && $result_fq->id_equipo && $id_parametro == 234){
	        //$salida="Calcula ALCALINIDAD";
	        
	        $fila_factor = procesar_registro_fetch ('factor_fq', 'id_factor', $result_fq->id_factor);
	        $fila_factor = $fila_factor[0];
	        $fila_factor->valor = str_replace(",",".",$fila_factor->valor);
	        
	        $result =  ( ( $result_fq->result_4 - $result_fq->result_3 ) * $result_fq->result_2 * $fila_factor->valor  )  /  $result_fq->result_1   ;
	        
	        $nombre_campo_frm ="resultado_ALCALINIDAD";
	        $nombre_campo_frm_irca="resultado_alcalinidad_irca";
	        //calculamos el IRCA
	        $salida[1].=calcula_irca_parcial($id_ensayo_vs_muestra, $id_parametro, $result);
	        
	    }
	    //nitritos 244
	    elseif(isset($result_fq->result_2)  && $result_fq->id_equipo && $id_parametro ==244){
	        //$salida="Calcula nitritos";
	        
	        $result =   $result_fq->result_2;
	        $nombre_campo_frm ="resultado_nitritos";
	        $nombre_campo_frm_irca="resultado_nitritos_irca";
	        //calculamos el IRCA
	        $salida[1].=calcula_irca_parcial($id_ensayo_vs_muestra, $id_parametro, $result);
	        
	    }
	     //hierro 239
	    elseif(isset($result_fq->result_2)   && $result_fq->id_equipo && $id_parametro ==239){
	        //$salida="Calcula hierro";
	        
	        $result =   $result_fq->result_2;
	        $nombre_campo_frm ="resultado_hierro";
	        $nombre_campo_frm_irca="resultado_hierro_irca";
	        //calculamos el IRCA
	        $salida[1].=calcula_irca_parcial($id_ensayo_vs_muestra, $id_parametro, $result);
	        
	    }
	    
	    //Fostatos 238
	    elseif(isset($result_fq->result_2)   && $result_fq->id_equipo && $id_parametro ==238){
	        //$salida="Calcula Fostatos";
	        
	        $result =   $result_fq->result_2;
	        $nombre_campo_frm ="resultado_fostatos";
	        $nombre_campo_frm_irca="resultado_fostatos_irca";
	        //calculamos el IRCA
	        $salida[1].=calcula_irca_parcial($id_ensayo_vs_muestra, $id_parametro, $result);
	        
	    }
	    //aluminio 242
	    elseif(isset($result_fq->result_2)   && $result_fq->id_equipo && $id_parametro ==242){
	        //$salida="Calcula aluminio";
	        
	        $result =   $result_fq->result_2;
	        $nombre_campo_frm ="resultado_aluminio";
	        $nombre_campo_frm_irca="resultado_aluminio_irca";
	        //calculamos el IRCA
	        $salida[1].=calcula_irca_parcial($id_ensayo_vs_muestra, $id_parametro, $result);
	        
	    }
	    //nitratos 245
	    elseif(isset($result_fq->result_2)  && $result_fq->id_equipo && $id_parametro ==245){
	        //$salida="Calcula nitratos";
	        
	        $result =   $result_fq->result_2;
	        $nombre_campo_frm ="resultado_nitratos";
	        $nombre_campo_frm_irca="resultado_nitratos_irca";
	        //calculamos el IRCA
	        $salida[1].=calcula_irca_parcial($id_ensayo_vs_muestra, $id_parametro, $result);
	        
	    }
	    //DUREZA 236
	    elseif(isset($result_fq->result_1) && isset($result_fq->result_2) && isset($result_fq->result_3)  && $result_fq->id_factor && $result_fq->id_equipo && $id_parametro ==236){
	        //$salida="Calcula dureza";
	        
	        $fila_factor = procesar_registro_fetch ('factor_fq', 'id_factor', $result_fq->id_factor);
	        $fila_factor = $fila_factor[0];
	        $fila_factor->valor = str_replace(",",".",$fila_factor->valor);
	        
	        $result =  ( ( $result_fq->result_2 * $result_fq->result_3 ) / $result_fq->result_1 )  * $fila_factor->valor;
	        
	        $nombre_campo_frm ="resultado_dureza";
	        $nombre_campo_frm_irca="resultado_dureza_irca";
	        //calculamos el IRCA
	        $salida[1].=calcula_irca_parcial($id_ensayo_vs_muestra, $id_parametro, $result);
	        
	    }
	    //CLORUROS 235
	    elseif(isset($result_fq->result_1) && isset($result_fq->result_2) && isset($result_fq->result_3) && isset($result_fq->result_4)  && $result_fq->id_factor && $result_fq->id_equipo && $id_parametro ==235){
	        //$salida="Calcula cloruros";
	        
	        $fila_factor = procesar_registro_fetch ('factor_fq', 'id_factor', $result_fq->id_factor);
	        $fila_factor = $fila_factor[0];
	        $fila_factor->valor = str_replace(",",".",$fila_factor->valor);
	        
	        $result =   ( ( $result_fq->result_4 - $result_fq->result_3 ) *  $result_fq->result_2  * $fila_factor->valor )/ $result_fq->result_1 ;
	        
	        $nombre_campo_frm ="resultado_cloruros";
	        $nombre_campo_frm_irca="resultado_cloruros_irca";
	        //calculamos el IRCA
	        $salida[1].=calcula_irca_parcial($id_ensayo_vs_muestra, $id_parametro, $result);
	        
	    }
	    //sulfatos 240
	    elseif(isset($result_fq->result_2) && isset($result_fq->result_3) && isset($result_fq->result_4) && isset($result_fq->result_6)  && $result_fq->id_equipo && $id_parametro ==240){
	        //$salida="Calcula sulfatos";
	        
	        $result =   ( ( $result_fq->result_3 - $result_fq->result_2 ) -  $result_fq->result_4 )/ $result_fq->result_6 ;
	        
	        $nombre_campo_frm ="resultado_sulfatos";
	        $nombre_campo_frm_irca="resultado_sulfatos_irca";
	        //calculamos el IRCA
	        $salida[1].=calcula_irca_parcial($id_ensayo_vs_muestra, $id_parametro, $result);
	        
	    }
	    
	    //solidos_totales 39 y 40
	    elseif(isset($result_fq->result_2) && isset($result_fq->result_3) && isset($result_fq->result_4)  && ($id_parametro ==39 || $id_parametro == 40)){
	        //$salida="Calcula sulfatos";
	        $result =   ( ( $result_fq->result_1 - $result_fq->result_2 ) * 1000000 )/ $result_fq->result_3 ;
	        
	        $nombre_campo_frm ="resultado_solidos_totales";
	        $nombre_campo_frm_irca="resultado_solidos_totales_irca";
	        //calculamos el IRCA
	        $salida[1].=calcula_irca_parcial($id_ensayo_vs_muestra, $id_parametro, $result);
	        
	    }
	    else{
	        $salida[0] ="pendiente de datos ";
	    }
	    
	    //comprobamos que el lleno un resuldado
	    if($nombre_campo_frm){
	        $result =   round($result, 6);
	        
	        //pasamos el resultado de punto a coma
	        $result = str_replace(".",",",$result);
	        
	        //$salida[0] = "<b>". $result  ." 2:".$result_fq->result_2 ."  3:".$result_fq->result_3 ."  4:".$result_fq->result_4."</b>";
	        $salida[0] = "<b>". $result ."</b>";
	        
	        //guarda campo en result_fq
	        almacena_campo_fq($id_ensayo_vs_muestra, $result, $id_parametro, $nombre_campo_frm, 'result_5');
	        //guarda resultado para certificado
	        almacena_primer_campo($id_ensayo_vs_muestra, $result);
	       	almacena_campo_resultado($id_ensayo_vs_muestra, $result);
        
        	//guarda valor parcial irca si existe
        	// $aux_irca = str_replace(".",",",$aux_irca);
	        if( isset($salida[1]) ){
	            almacena_campo_fq($id_ensayo_vs_muestra, $salida[1], $id_parametro, $nombre_campo_frm_irca, 'result_irca');    
	        }
    	}
    	return $salida;   
	}
	function calcula_irca_parcial($id_ensayo_vs_muestra, $id_parametro, $result){
    	//calculamos el IRCA
        //1. averiguamos el rango de medicion
        //2. vemos si esta en el rango
        //3. si esta el IRCA es 0
        //4. si no esta el IRCA es el valor por defecto que esta en el parametro
        $fila_ensayo_vs_muestra = procesar_registro_fetch('ensayo_vs_muestra', 'id_ensayo_vs_muestra', $id_ensayo_vs_muestra);
        $fila_ensayo = procesar_registro_fetch('ensayo', 'id_ensayo', $fila_ensayo_vs_muestra[0]->id_ensayo, 'id_parametro', $id_parametro);
        $fila_parametro = procesar_registro_fetch('parametro', 'id_parametro', $id_parametro);
        
        //NOTA. Si no solicitaron el parametro 243 IRCA no se calculda
        $fila_ensayo_vs_muestra_irca = procesar_registro_fetch('ensayo', 'id_ensayo', $fila_ensayo_vs_muestra[0]->id_ensayo, 'id_parametro', 243);
       
        if($fila_ensayo_vs_muestra_irca[0]){
            $aux_irca ="No solicitado";
        }
       
        //formateo de rango minimo
            $med_valor_min = ($fila_ensayo[0]->med_valor_min) ? $fila_ensayo[0]->med_valor_min:-0.999999;
            $med_valor_min = str_replace(",",".",$med_valor_min);
        //formateo de rango maximo.
            $med_valor_max = formatea_valor_min_max($fila_ensayo[0]->med_valor_max);
            $med_valor_max = str_replace(",",".",$med_valor_max);
         
            $salida = 'Rango '.$med_valor_min.' a  '.$med_valor_max.' irca '.$fila_parametro[0]->par_irca .'  resultado '.$result;
         
            $aux_irca =($med_valor_min <= $result  &&   $result <= $med_valor_max )?0:$fila_parametro[0]->par_irca;//
            
            $salida .=' resultado IRCA '.$aux_irca;
            
            return  $aux_irca;
           // return  $salida;
	}
	function calcula_IRCA($id_ensayo_vs_muestra){
    
	    //preguntamos si solicitaron IRCA 243
	    //si lo tiene buscamos que procesos tiene 
	    //1. Cloro Libre agua SM id_parametro 101
	    //2. pH Agua SM  id_parametro 86 
	    //3.  TURBIEDAD Agua SM  id_parametro 241
	    //4. COLOR  SM  id_parametro 237
	    //5. ALCALINIDAD TOTAL    id_parametro 234  
	    //6.  DUREZA TOTAL id_parametro 236
	    //7.  Cloruros  id_parametro 235
	    //8.  SULFATOS  id_parametro 240
	    //9. Nitritos id_parametro 244 
	    //10. HIERRO id_parametro 239
	    //11.  Fostatos SM id_parametro 238
	    //12.  Aluminio SM id_parametro 242 
	    //13. nitratos SM id_parametro 245 
	    //14.  resultado de microbiologico ( almacenado en el id 243 del campo result_1)
	    //si todos los que tiene estan calculados se calcula IRCA
	     //preguntamos si tiene IRCA
        $result_e_v_m = procesar_registro_fetch('ensayo_vs_muestra', 'id_ensayo_vs_muestra', $id_ensayo_vs_muestra);
        $result_e_v_m = $result_e_v_m[0];
        $fila = fq_tiene_parametro($result_e_v_m->id_muestra, 243);
        if (!empty($fila[0])){
            $salida="Tiene IRCA";
            $aux_todos_llenos ="Si";
            $aux_total_castigo=0;
            $aux_total_analizados=0;
            //1. Cloro Libre agua SM id_parametro 101
            $fila_cloro = fq_tiene_parametro($result_e_v_m->id_muestra, 101);
            if (!empty($fila_cloro[0])){
                $salida.="<br>Si tiene CLORO LIBRE";
                $result_cloro = procesar_registro_fetch('ensa_mues_fq', 'id_ensayo_vs_muestra', $fila_cloro[0]->id_ensayo_vs_muestra);
                if($result_cloro[0]->result_irca>=0){
                        $salida.="".  $result_cloro[0]->result_irca;
                        $aux_total_castigo = $aux_total_castigo + str_replace(",",".",$result_cloro[0]->result_irca);
                        $fila_irca = procesar_registro_fetch('parametro', 'id_parametro', 101);
                        $salida.=" irca: ".  $fila_irca[0]->par_irca;
                        $aux_total_analizados = $aux_total_analizados + str_replace(",",".",$fila_irca[0]->par_irca);
                        
                }else{
                    $salida.='Sin resultado';
                    $aux_todos_llenos ="No";
                }
            }else{
                $salida.="No tiene CLORO LIBRE ";
            }
            //2. pH Agua SM  id_parametro 86 
            $fila_ph = fq_tiene_parametro($result_e_v_m->id_muestra, 86);
            if (!empty($fila_ph[0])){
                $salida.="<br>Si tiene pH";
                $result_ph = procesar_registro_fetch('ensa_mues_fq', 'id_ensayo_vs_muestra', $fila_ph[0][id_ensayo_vs_muestra]);
                if($result_ph[0]->result_irca>=0){
                        $salida.="".  $result_ph[0]->result_irca;
                        $aux_total_castigo = $aux_total_castigo + str_replace(",",".",$result_ph[0]->result_irca);
                        $fila_irca = procesar_registro_fetch('parametro', 'id_parametro', 86);
                        $salida.=" irca: ".  $fila_irca[0]->par_irca;
                        $aux_total_analizados = $aux_total_analizados + str_replace(",",".",$fila_irca[0]->par_irca);
                        
                }else{
                    $salida.='Sin resultado';
                    $aux_todos_llenos ="No";
                }
            }else{
                $salida.="No tiene pH";
            }
            //3.  TURBIEDAD Agua SM  id_parametro 241
            $fila_turb = fq_tiene_parametro($result_e_v_m->id_muestra, 241);
            if (!empty($fila_turb[0])){
                $salida.="<br>Si tiene Turbiedad";
                $result_turb = procesar_registro_fetch('ensa_mues_fq', 'id_ensayo_vs_muestra', $fila_turb[0]->id_ensayo_vs_muestra);
                if($result_turb[0]->result_irca>=0){
                        $salida.="".  $result_turb[0]->result_irca;
                        $aux_total_castigo = $aux_total_castigo + str_replace(",",".",$result_turb[0]->result_irca);
                        $fila_irca = procesar_registro_fetch('parametro', 'id_parametro', 241);
                        $salida.=" irca: ".  $fila_irca[0]->par_irca;
                        $aux_total_analizados = $aux_total_analizados + str_replace(",",".",$fila_irca[0]->par_irca);
                        
                }else{
                    $salida.='Sin resultado';
                    $aux_todos_llenos ="No";
                }
            }else{
                $salida.="No tiene TURBIEDAD ";
            }
            //4. COLOR  SM  id_parametro 237
            $fila_color = fq_tiene_parametro($result_e_v_m->id_muestra, 237);
            if (!empty($fila_color[0])){
                $salida.="<br>Si tiene COLOR ";
                $result_color = procesar_registro_fetch('ensa_mues_fq', 'id_ensayo_vs_muestra', $fila_color[0]->id_ensayo_vs_muestra);
                if($result_color[0]->result_irca>=0){
                        $salida.="".  $result_color[0]->result_irca;
                        $aux_total_castigo = $aux_total_castigo + str_replace(",",".",$result_color[0]->result_irca);
                        $fila_irca = procesar_registro_fetch('parametro', 'id_parametro', 237);
                        $salida.=" irca: ".  $fila_irca[0]->par_irca;
                        $aux_total_analizados = $aux_total_analizados + str_replace(",",".",$fila_irca[0]->par_irca);
                        
                }else{
                    $salida.='Sin resultado';
                    $aux_todos_llenos ="No";
                }
            }else{
                $salida.="No tiene COLOR ";
            }
            //5. ALCALINIDAD TOTAL    id_parametro 234  
            $fila_alca = fq_tiene_parametro($result_e_v_m->id_muestra, 234);
            if (!empty($fila_alca[0])){
                $salida.="<br>Si tiene ALCALINIDAD";
                $result_alca = procesar_registro_fetch('ensa_mues_fq', 'id_ensayo_vs_muestra', $fila_alca[0]->id_ensayo_vs_muestra);
                if($result_alca[0]->result_irca>=0){
                        $salida.=" ".  $result_alca[0]->result_irca;
                        $aux_total_castigo = $aux_total_castigo + str_replace(",",".",$result_alca[0]->result_irca);
                        $fila_irca = procesar_registro_fetch('parametro', 'id_parametro', 234);
                        $salida.=" irca: ".  $fila_irca[0]->par_irca;
                        $aux_total_analizados = $aux_total_analizados + str_replace(",",".",$fila_irca[0]->par_irca);
                        
                }else{
                    $salida.='Sin resultado';
                    $aux_todos_llenos ="No";
                }
            }else{
                $salida.="No tiene ALCALINIDAD ";
            }
            //6.  DUREZA TOTAL id_parametro 236
            $fila_dure = fq_tiene_parametro($result_e_v_m->id_muestra, 236);
            if (!empty($fila_dure[0])){
                $salida.="<br>Si tiene DUREZA";
                $result_dure = procesar_registro_fetch('ensa_mues_fq', 'id_ensayo_vs_muestra', $fila_dure[0]->id_ensayo_vs_muestra);
                if($result_dure[0]->result_irca>=0){
                        $salida.="".  $result_dure[0]->result_irca;
                        $aux_total_castigo = $aux_total_castigo + str_replace(",",".",$result_dure[0]->result_irca);
                        $fila_irca = procesar_registro_fetch('parametro', 'id_parametro', 236);
                        $salida.=" irca: ".  $fila_irca[0]->par_irca;
                        $aux_total_analizados = $aux_total_analizados + str_replace(",",".",$fila_irca[0]->par_irca);
                        
                }else{
                    $salida.='Sin resultado';
                    $aux_todos_llenos ="No";
                }
            }else{
                $salida.="No tiene DUREZA ";
            }
            //7.  Cloruros  id_parametro 235
            $fila_cloruro = fq_tiene_parametro($result_e_v_m->id_muestra, 235);
            if (!empty($fila_cloruro[0])){
                $salida.="<br>Si tiene CLORUROS";
                $result_cloruro = procesar_registro_fetch('ensa_mues_fq', 'id_ensayo_vs_muestra', $fila_cloruro[0]->id_ensayo_vs_muestra);
                if($result_cloruro[0]->result_irca>=0){
                        $salida.="".  $result_cloruro[0]->result_irca;
                        $aux_total_castigo = $aux_total_castigo + str_replace(",",".",$result_cloruro[0]->result_irca);
                        $fila_irca = procesar_registro_fetch('parametro', 'id_parametro', 235);
                        $salida.=" irca: ".  $fila_irca[0]->par_irca;
                        $aux_total_analizados = $aux_total_analizados + str_replace(",",".",$fila_irca[0]->par_irca);
                        
                }else{
                    $salida.='Sin resultado';
                    $aux_todos_llenos ="No";
                }
            }else{
                $salida.="No tiene CLORUROS ";
            }
             //8.  SULFATOS  id_parametro 240
            $fila_sulfa = fq_tiene_parametro($result_e_v_m->id_muestra, 240);
            if (!empty($fila_sulfa[0])){
                $salida.="<br>Si tiene SULFATOS";
                $result_sulfa = procesar_registro_fetch('ensa_mues_fq', 'id_ensayo_vs_muestra', $fila_sulfa[0]->id_ensayo_vs_muestra);
                if($result_sulfa[0]->result_irca>=0){
                        $salida.="".  $result_sulfa[0]->result_irca;
                        $aux_total_castigo = $aux_total_castigo + str_replace(",",".",$result_sulfa[0]->result_irca);
                        $fila_irca = procesar_registro_fetch('parametro', 'id_parametro', 240);
                        $salida.=" irca: ".  $fila_irca[0]->par_irca;
                        $aux_total_analizados = $aux_total_analizados + str_replace(",",".",$fila_irca[0]->par_irca);
                        
                }else{
                    $salida.='Sin resultado';
                    $aux_todos_llenos ="No";
                }
            }else{
                $salida.="No tiene SULFATOS ";
            }
           //9. Nitritos id_parametro 244 
            $fila_nitri = fq_tiene_parametro($result_e_v_m->id_muestra, 244);
            if (!empty($fila_nitri[0])){
                $salida.="<br>Si tiene NITRITOS";
                $result_nitri = procesar_registro_fetch('ensa_mues_fq', 'id_ensayo_vs_muestra', $fila_nitri[0]->id_ensayo_vs_muestra);
                if($result_nitri[0]->result_irca>=0){
                        $salida.="".  $result_nitri[0]->result_irca;
                        $aux_total_castigo = $aux_total_castigo + str_replace(",",".",$result_nitri[0]->result_irca);
                        $fila_irca = procesar_registro_fetch('parametro', 'id_parametro', 244);
                        $salida.=" irca: ".  $fila_irca[0]->par_irca;
                        $aux_total_analizados = $aux_total_analizados + str_replace(",",".",$fila_irca[0]->par_irca);
                        
                }else{
                    $salida.='Sin resultado';
                    $aux_todos_llenos ="No";
                }
            }else{
                $salida.="No tiene NITRITOS ";
            }
             //10. HIERRO id_parametro 239
            $fila_hierro = fq_tiene_parametro($result_e_v_m->id_muestra, 239);
            if (!empty($fila_hierro[0])){
                $salida.="<br>Si tiene HIERRO";
                $result_hierro = procesar_registro_fetch('ensa_mues_fq', 'id_ensayo_vs_muestra', $fila_hierro[0]->id_ensayo_vs_muestra);
                if($result_hierro[0]->result_irca>=0){
                        $salida.="".  $result_hierro[0]->result_irca;
                        $aux_total_castigo = $aux_total_castigo + str_replace(",",".",$result_hierro[0]->result_irca);
                        $fila_irca = procesar_registro_fetch('parametro', 'id_parametro', 239);
                        $salida.=" irca: ".  $fila_irca[0]->par_irca;
                        $aux_total_analizados = $aux_total_analizados + str_replace(",",".",$fila_irca[0]->par_irca);
                        
                }else{
                    $salida.='Sin resultado';
                    $aux_todos_llenos ="No";
                }
            }else{
                $salida.="No tiene HIERRO ";
            }
            //11.  Fostatos SM id_parametro 238
            $fila_fosfa = fq_tiene_parametro($result_e_v_m->id_muestra, 238);
            if (!empty($fila_fosfa[0])){
                $salida.="<br>Si tiene FOSFATOS";
                $result_fosfa = procesar_registro_fetch('ensa_mues_fq', 'id_ensayo_vs_muestra', $fila_fosfa[0]->id_ensayo_vs_muestra);
                if($result_fosfa[0]->result_irca>=0){
                        $salida.="".  $result_fosfa[0]->result_irca;
                        $aux_total_castigo = $aux_total_castigo + str_replace(",",".",$result_fosfa[0]->result_irca);
                        $fila_irca = procesar_registro_fetch('parametro', 'id_parametro', 238);
                        $salida.=" irca: ".  $fila_irca[0]->par_irca;
                        $aux_total_analizados = $aux_total_analizados + str_replace(",",".",$fila_irca[0]->par_irca);
                        
                }else{
                    $salida.='Sin resultado';
                    $aux_todos_llenos ="No";
                }
            }else{
                $salida.="No tiene FOSFATOS ";
            }
            //12.  Aluminio SM id_parametro 242 
            $fila_alum = fq_tiene_parametro($result_e_v_m->id_muestra, 242);
            if (!empty($fila_alum[0])){
                $salida.="<br>Si tiene ALUMINIO";
                $result_alum = procesar_registro_fetch('ensa_mues_fq', 'id_ensayo_vs_muestra', $fila_alum[0]->id_ensayo_vs_muestra);
                if($result_alum[0]->result_irca>=0){
                        $salida.="".  $result_alum[0]->result_irca;
                        $aux_total_castigo = $aux_total_castigo + str_replace(",",".",$result_alum[0]->result_irca);
                        $fila_irca = procesar_registro_fetch('parametro', 'id_parametro', 242);
                        $salida.=" irca: ".  $fila_irca[0]->par_irca;
                        $aux_total_analizados = $aux_total_analizados + str_replace(",",".",$fila_irca[0]->par_irca);
                        
                }else{
                    $salida.='Sin resultado';
                    $aux_todos_llenos ="No";
                }
            }else{
                $salida.="No tiene ALUMINIO ";
            }
           //13. nitratos SM id_parametro 245 
            $fila_nitra = fq_tiene_parametro($result_e_v_m->id_muestra, 245);
            if (!empty($fila_nitra[0])){
                $salida.="<br>Si tiene NITRATOS";
                $result_nitra = procesar_registro_fetch('ensa_mues_fq', 'id_ensayo_vs_muestra', $fila_nitra[0]->id_ensayo_vs_muestra);
                if($result_nitra[0]->result_irca>=0){
                        $salida.="".  $result_nitra[0]->result_irca;
                        $aux_total_castigo = $aux_total_castigo + str_replace(",",".",$result_nitra[0]->result_irca);
                        $fila_irca = procesar_registro_fetch('parametro', 'id_parametro', 245);
                        $salida.=" irca: ".  $fila_irca[0]->par_irca;
                        $aux_total_analizados = $aux_total_analizados + str_replace(",",".",$fila_irca[0]->par_irca);
                        
                }else{
                    $salida.='Sin resultado';
                    $aux_todos_llenos ="No";
                }
            }else{
                $salida.="No tiene NITRATOS ";
            }
            // si tiene resultados microbiologico
            $result_micro = procesar_registro_fetch('ensa_mues_fq', 'id_ensayo_vs_muestra', $fila[0]->id_ensayo_vs_muestra);
            if($result_micro[0]->result_1>=0){
                $salida.="<br>Si tiene MICRO ";
                $salida.="".  $result_micro[0]->result_1;
                $aux_total_castigo = $aux_total_castigo + str_replace(",",".",$result_micro[0]->result_1);
                $aux_total_analizados = $aux_total_analizados + 40;
                
            }
            
           
            
            
                $salida.="<br> Total castigo: ".$aux_total_castigo;
                $salida.="<br> Total analizados: ".$aux_total_analizados;
                
            if($aux_todos_llenos=="Si"){
                $aux_total_castigo = $aux_total_castigo;
                $result  = ($aux_total_castigo / $aux_total_analizados) *100;
                     
                $result =   round($result, 2);
                //pasamos el resultado de punto a coma
                $result = str_replace(".",",",$result);


                almacena_campo_fq($fila[0]->id_ensayo_vs_muestra, $result, 243, 'resultado_irca', 'result_irca');
                        
                //guarda resultado para certificado
                almacena_primer_campo($fila[0]->id_ensayo_vs_muestra, $result);
                almacena_campo_resultado($fila[0]->id_ensayo_vs_muestra, $result);
                $salida = $result;
            }else{
                $salida = "Sin resultados";
            }
            
            
        }else{
                $salida.="No Tiene IRCA";
        }
    	return $salida;
	}
?>