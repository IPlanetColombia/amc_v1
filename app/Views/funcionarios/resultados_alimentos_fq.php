<?= view('layouts/header') ?>
<?= view('layouts/navbar_vertical') ?>
<?= view('layouts/navbar_horizontal') ?>
    <div id="main">
        <div class="row">
            <div class="col s12">
                <div class="container">
                    <div class="row">
                        <div class="col s12">
                            <div class="card animate fadeUp">
                                <div class="card-content">
                                    <h2 class="card-title">
                                        Registro de Muestras FisicoQuímicas <?= $certificado ? $certificado->id_codigo_amc:'' ?>
                                    </h2>
                                    <hr>
<div class="row">
    <form class="col s12" method="POST" action="<?= base_url(['funcionario', 'resultados', 'alimentos']) ?>">
        <div class="row">
            <div class="input-field col s12 l6">
                <input id="frm_anio_busca" name="frm_anio_busca" type="text" class="validate">
                <label for="frm_anio_busca">Año:</label>
                <small class="red-text text-darken-2"><?= $validation->getError('frm_anio_busca') ?></small>
            </div>
            <div class="input-field col s12 l6">
                <input id="frm_codigo_busca" name="frm_codigo_busca" type="text" class="validate">
                <label for="frm_codigo_busca">C&oacute;digo muestra:</label>
                <small class="red-text text-darken-2"><?= $validation->getError('frm_codigo_busca') ?></small>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 l12 centrar_button">
                <button class="btn gradient-45deg-purple-deep-orange border-round">
                    <i class="fas fa-search"></i> Buscar
                </button>
            </div>
        </div>
    </form>
</div>
    <?php if ($certificado->id_muestreo): ?>
        <?php $equipos = procesar_registro_fetch('equipo_fq', 'estado', 'Activo') ?>
        <?php $factores = procesar_registro_fetch('factor_fq', 'estado', 'Activo') ?>
        <div class="row">
            <div class="col l12 z-depth-2">
                <form id="form_cambia_campos" method="POST" action="<?= base_url(['funcionario', 'resultados', 'alimentos', 'cambiar', 'fq']) ?>">
                    <table class="striped centered">
                        <thead>
                            <tr>
                                <th>
                                    <p class="card-title"><b><?= $certificado->mue_identificacion ?></b></p>
                                    <b>Analisis: <?= $certificado->pro_nombre ?></b>
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $humedad = fq_tiene_parametro($certificado->id_muestra_detalle, 24,25);?>
                            <?php if (!empty($humedad[0])): ?>
                                <?php 
                                    $result_humedad = procesar_registro_fetch('ensa_mues_fq', 'id_ensayo_vs_muestra', $humedad[0]->id_ensayo_vs_muestra, 'id_parametro', 24);
                                ?>
                                <tr>
                                    <td>
                                        <p class="center-align"><b>Humedad</b></p>
                                        <div class="row">
                                            <div class="input-field col s12 l2">
                                                <input type="text" name="frm_humedad_peso_vacio" id="frm_humedad_peso_vacio" value="<?= $result_humedad[0]->result_1 ?>" <?= disable_frm($result_humedad[0]->result_1, session('user')->usr_rol) ?>
                                                onblur="js_cambiar_campos('campo_repuesta_1_<?= $humedad[0]->id_ensayo_vs_muestra?>',this.value, 'frm_humedad_peso_vacio', 'result_1', '<?= $humedad[0]->id_ensayo_vs_muestra ?>', 24)">
                                                <label for="frm_humedad_peso_vacio">Peso vacío (g)</label>
                                                <span id="frm_humedad_peso_vacio"></span>
                                            </div>
                                            <div class="input-field col s12 l2">
                                                <input type="text" name="frm_humedad_peso_muestra" id="frm_humedad_peso_muestra" value="<?= $result_humedad[0]->result_2 ?>" <?= disable_frm($result_humedad[0]->result_2, session('user')->usr_rol) ?>
                                                onblur="js_cambiar_campos('campo_repuesta_1_<?= $humedad[0]->id_ensayo_vs_muestra?>',this.value, 'frm_humedad_peso_muestra', 'result_2', '<?= $humedad[0]->id_ensayo_vs_muestra ?>', 24)">
                                                <label for="frm_humedad_peso_muestra">Peso de la muestra (g)</label>
                                                <span id="frm_humedad_peso_muestra"></span>
                                            </div>
                                            <div class="input-field col s12 l2">
                                                <input type="text" name="frm_humedad_peso_seco" id="frm_humedad_peso_seco" value="<?= $result_humedad[0]->result_3 ?>" <?= disable_frm($result_humedad[0]->result_3, session('user')->usr_rol) ?>
                                                onblur="js_cambiar_campos('campo_repuesta_1_<?= $humedad[0]->id_ensayo_vs_muestra?>',this.value, 'frm_humedad_peso_seco', 'result_3', '<?= $humedad[0]->id_ensayo_vs_muestra ?>', 24)">
                                                <label for="frm_humedad_peso_seco">Peso seco (g)</label>
                                                <span id="frm_humedad_peso_seco"></span>
                                            </div>
                                            <div class="col s12 l3">
                                                <p><b>% Humedad</b></p>
                                                <b id="campo_repuesta_mensaje_<?= $humedad[0]->id_ensayo_vs_muestra ?>"><?= $result_humedad[0]->result_5 ?> %</b>
                                            </div>
                                            <div class="input-field col s12 l3">
                                                <select name="frm_humedad_equipo"
                                                        id="frm_humedad_equipo"
                                                        onchange="js_cambiar_campos('campo_repuesta_equipo_<?= $humedad[0]->id_ensayo_vs_muestra?>',this.value, 'frm_humedad_equipo', 'id_equipo', '<?= $humedad[0]->id_ensayo_vs_muestra?>', 24)"
                                                        <?= disable_frm($result_humedad[0]->id_equipo, session('user')->usr_rol) ?>>
                                                    <option value="0">Seleccione equipo</option>
                                                    <?php foreach ($equipos as $key => $equipo): ?>
                                                        <option value="<?= $equipo->id_equipo ?>" <?= $equipo->id_equipo==$result_humedad[0]->id_equipo ?'selected':''; ?>><?= $equipo->nombre ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                                <label>Codigo equipo</label>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif ?>
                            <?php $solidosT = fq_tiene_parametro($certificado->id_muestra_detalle, 39) ?>
                            <?php if (!empty($solidosT[0])): ?>
                                <tr>
                                    <td>
                                        <p class="center-align"><b>Solidos Totales</b></p>
                                        <div class="row">
                                            <div class="col l12">
                                                <p><b>% Solidos Totales</b></p>
                                                <b id="campo_resultado_st">
                                                    <?php if (!empty($solidosT->resultado_mensaje)): ?>
                                                        <?= $solidosT->resultado_mensaje ?> %
                                                    <?php else: ?>
                                                        Sin resultado
                                                    <?php endif ?>
                                                </b>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif ?>
                            <?php $proteina = fq_tiene_parametro($certificado->id_muestra_detalle, 25) ?>
                            <?php if (!empty($proteina[0])): ?>
                                <?php $result_proteina = procesar_registro_fetch('ensa_mues_fq', 'id_ensayo_vs_muestra', $proteina[0]->id_ensayo_vs_muestra, 'id_parametro', 25) ?>
                                <tr>
                                    <td>
                                        <p class="center-align"><b>Proteina</b></p>
                                        <div class="row">
                                            <div class="input-field col s12 l3">
                                                <input type="text" name="frm_proteina_peso_muestra"
                                                id="frm_proteina_peso_muestra" value="<?= $result_proteina[0]->result_1 ?>"
                                                onblur="js_cambiar_campos('campo_repuesta_1_<?= $proteina[0]->id_ensayo_vs_muestra ?>', this.value, 'frm_proteina_peso_muestra', 'result_1', '<?= $proteina[0]->id_ensayo_vs_muestra ?>', 25)"
                                                <?= disable_frm($result_proteina[0]->result_1, session('user')->id) ?>>
                                                <label for="frm_proteina_peso_muestra">Peso de la muestra (g)</label>
                                                <span id="frm_proteina_peso_muestra"></span>
                                            </div>
                                            <div class="input-field col s12 l3">
                                                <input type="text" name="frm_proteina_vol_gastado" id="frm_proteina_vol_gastado" value="<?= $result_proteina[0]->result_2 ?>"
                                                onblur="js_cambiar_campos('campo_repuesta_2_<?= $proteina[0]->id_ensayo_vs_muestra ?>', this.value, 'frm_proteina_vol_gastado', 'result_2', '<?= $proteina[0]->id_ensayo_vs_muestra ?>', 25)"
                                                <?= disable_frm($result_proteina[0]->result_2, session('user')->id) ?>>
                                                <label for="frm_proteina_vol_gastado">Vol gastado blanco</label>
                                                <span id="frm_proteina_vol_gastado"></span>
                                            </div>
                                            <div class="input-field col s12 l3">
                                                <input type="text" name="frm_proteina_ml_h_gastado" id="frm_proteina_ml_h_gastado" value="<?= $result_proteina[0]->result_3 ?>"
                                                onblur="js_cambiar_campos('campo_repuesta_3_<?= $proteina[0]->id_ensayo_vs_muestra ?>', this.value, 'frm_proteina_ml_h_gastado', 'result_3', '<?= $proteina[0]->id_ensayo_vs_muestra ?>', 25)"
                                                <?= disable_frm($result_proteina[0]->result_3, session('user')->id) ?>>
                                                <label for="frm_proteina_ml_h_gastado">ml de H+ Gastado</label>
                                                <span id="frm_proteina_ml_h_gastado"></span>
                                            </div>
                                            <div class="input-field col s12 l3">
                                                <input type="text" name="frm_proteina_N_Hplus" id="frm_proteina_N_Hplus" value="<?= $result_proteina[0]->result_4 ?>"
                                                onblur="js_cambiar_campos('campo_repuesta_4_<?= $proteina[0]->id_ensayo_vs_muestra ?>', this.value, 'frm_proteina_N_Hplus', 'result_4', '<?= $proteina[0]->id_ensayo_vs_muestra ?>', 25)"
                                                <?= disable_frm($result_proteina[0]->result_4, session('user')->id) ?>>
                                                <label for="frm_proteina_N_Hplus">N[H+]</label>
                                                <span id="frm_proteina_N_Hplus"></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-field col s12 l4">
                                                <?php $factores = procesar_registro_fetch('factor_fq', 'estado', 'Activo') ?>
                                                <select name="frm_proteina_factor"
                                                        id="frm_proteina_factor"
                                                        onchange="js_cambiar_campos('campo_repuesta_factor_<?= $proteina[0]->id_ensayo_vs_muestra?>',this.value, 'frm_proteina_factor', 'id_factor', '<?= $proteina[0]->id_ensayo_vs_muestra?>', 25)"
                                                        <?= disable_frm($result_humedad[0]->id_factor, session('user')->usr_rol) ?>>
                                                    <option>Seleccione factor</option>
                                                    <?php foreach ($factores as $key => $factor): ?>
                                                        <option value="<?= $factor->id_factor ?>" <?= $factor->id_factor==$result_proteina[0]->id_factor ?'selected':''; ?>><?= $factor->nombre.' | '.$factor->valor ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                                <label>Factor conver</label>
                                            </div>
                                            <div class="col s12 l4">
                                                <p><b>%Proteina</b></p>
                                                <b id="campo_repuesta_mensaje_<?= $proteina[0]->id_ensayo_vs_muestra ?>"><?= $result_proteina[0]->result_5 ?> %</b>
                                            </div>
                                            <div class="input-field col s12 l4">
                                                <select name="frm_proteina_equipo"
                                                        id="frm_proteina_equipo"
                                                        onchange="js_cambiar_campos('campo_repuesta_equipo_<?= $proteina[0]->id_ensayo_vs_muestra?>',this.value, 'frm_proteina_equipo', 'id_equipo', '<?= $proteina[0]->id_ensayo_vs_muestra?>', 25)"
                                                        <?= disable_frm($result_proteina[0]->id_equipo, session('user')->usr_rol) ?>>
                                                    <option value="0">Seleccione equipo</option>
                                                    <?php foreach ($equipos as $key => $equipo): ?>
                                                        <option value="<?= $equipo->id_equipo ?>" <?= $equipo->id_equipo==$result_proteina[0]->id_equipo ?'selected':''; ?>><?= $equipo->nombre ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                                <label>Codigo equipo</label>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif ?>
                            <?php $fibra = fq_tiene_parametro($certificado->id_muestra_detalle, 26) ?>
                            <?php if (!empty($fibra[0])): ?>
                                <?php $result_fibra = procesar_registro_fetch('ensa_mues_fq', 'id_ensayo_vs_muestra', $fibra[0]->id_ensayo_vs_muestra, 'id_parametro', 26) ?>
                                <tr>
                                    <td>
                                        <p class="center-align"><b>Fibra</b></p>
                                        <div class="row">
                                            <div class="input-field col s12 l2">
                                                <input type="text" name="frm_fibra_peso_calcinada"
                                                id="frm_fibra_peso_calcinada" value="<?= $result_fibra[0]->result_1 ?>"
                                                onblur="js_cambiar_campos('campo_repuesta_1_<?= $fibra[0]->id_ensayo_vs_muestra ?>', this.value, 'frm_fibra_peso_calcinada', 'result_1', '<?= $fibra[0]->id_ensayo_vs_muestra ?>', 26)"
                                                <?= disable_frm($result_fibra[0]->result_1, session('user')->id) ?>>
                                                <label for="frm_fibra_peso_calcinada">Peso M. Calcinada (g)</label>
                                                <span id="frm_fibra_peso_calcinada"></span>
                                            </div>
                                            <div class="input-field col s12 l3">
                                                <input type="text" name="frm_fibra_peso_m_seca"
                                                id="frm_fibra_peso_m_seca" value="<?= $result_fibra[0]->result_2 ?>"
                                                onblur="js_cambiar_campos('campo_repuesta_2_<?= $fibra[0]->id_ensayo_vs_muestra ?>', this.value, 'frm_fibra_peso_m_seca', 'result_2', '<?= $fibra[0]->id_ensayo_vs_muestra ?>', 26)"
                                                <?= disable_frm($result_fibra[0]->result_2, session('user')->id) ?>>
                                                <label for="frm_fibra_peso_m_seca">PESO M SECA (g)</label>
                                                <span id="frm_fibra_peso_m_seca"></span>
                                            </div>
                                            <div class="input-field col s12 l2">
                                                <input type="text" name="frm_fibra_peso_muestra"
                                                id="frm_fibra_peso_muestra" value="<?= $result_fibra[0]->result_3 ?>"
                                                onblur="js_cambiar_campos('campo_repuesta_3_<?= $fibra[0]->id_ensayo_vs_muestra ?>', this.value, 'frm_fibra_peso_muestra', 'result_3', '<?= $fibra[0]->id_ensayo_vs_muestra ?>', 26)"
                                                <?= disable_frm($result_fibra[0]->result_3, session('user')->id) ?>>
                                                <label for="frm_fibra_peso_muestra">Peso muestra (g)</label>
                                                <span id="frm_fibra_peso_muestra"></span>
                                            </div>
                                            <div class="col s12 l2">
                                                <p><b>% Fibra</b></p>
                                                <b id="campo_repuesta_mensaje_<?= $fibra[0]->id_ensayo_vs_muestra ?>"><?= $result_fibra[0]->result_5 ?> %</b>
                                            </div>
                                            <div class="input-field col s12 l3">
                                                <select name="frm_fibra_equipo"
                                                        id="frm_fibra_equipo"
                                                        onchange="js_cambiar_campos('campo_repuesta_equipo_<?= $fibra[0]->id_ensayo_vs_muestra?>',this.value, 'frm_fibra_equipo', 'id_equipo', '<?= $fibra[0]->id_ensayo_vs_muestra?>', 26)"
                                                        <?= disable_frm($result_fibra[0]->id_equipo, session('user')->usr_rol) ?>>
                                                    <option value="0">Seleccione equipo</option>
                                                    <?php foreach ($equipos as $key => $equipo): ?>
                                                        <option value="<?= $equipo->id_equipo ?>" <?= $equipo->id_equipo==$result_fibra[0]->id_equipo ?'selected':''; ?>><?= $equipo->nombre ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                                <label>Codigo equipo</label>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif ?>
                            <?php $grasa = fq_tiene_parametro($certificado->id_muestra_detalle, 27) ?>
                            <?php if (!empty($grasa[0])): ?>
                                <?php $result_grasa = procesar_registro_fetch('ensa_mues_fq', 'id_ensayo_vs_muestra', $grasa[0]->id_ensayo_vs_muestra, 'id_parametro', 27) ?>
                                <tr>
                                    <td>
                                        <p class="center-align"><b>Grasa</b></p>
                                        <div class="row">
                                            <div class="input-field col s12 l2">
                                                <input type="text" name="frm_grasa_peso_balon_vacio"
                                                id="frm_grasa_peso_balon_vacio" value="<?= $result_grasa[0]->result_1 ?>"
                                                onblur="js_cambiar_campos('campo_repuesta_1_<?= $grasa[0]->id_ensayo_vs_muestra ?>', this.value, 'frm_grasa_peso_balon_vacio', 'result_1', '<?= $grasa[0]->id_ensayo_vs_muestra ?>', 27)"
                                                <?= disable_frm($result_grasa[0]->result_1, session('user')->id) ?>>
                                                <label for="frm_grasa_peso_balon_vacio">Peso balón vacio (g)</label>
                                                <span id="frm_grasa_peso_balon_vacio"></span>
                                            </div>
                                            <div class="input-field col s12 l3">
                                                <input type="text" name="frm_grasa_peso_muestra_inicial"
                                                id="frm_grasa_peso_muestra_inicial" value="<?= $result_grasa[0]->result_2 ?>"
                                                onblur="js_cambiar_campos('campo_repuesta_2_<?= $grasa[0]->id_ensayo_vs_muestra ?>', this.value, 'frm_grasa_peso_muestra_inicial', 'result_2', '<?= $grasa[0]->id_ensayo_vs_muestra ?>', 27)"
                                                <?= disable_frm($result_grasa[0]->result_2, session('user')->id) ?>>
                                                <label for="frm_grasa_peso_muestra_inicial">Peso muestra inicial (g)</label>
                                                <span id="frm_grasa_peso_muestra_inicial"></span>
                                            </div>
                                            <div class="input-field col s12 l2">
                                                <input type="text" name="frm_grasa_peso_final"
                                                id="frm_grasa_peso_final" value="<?= $result_grasa[0]->result_3 ?>"
                                                onblur="js_cambiar_campos('campo_repuesta_3_<?= $grasa[0]->id_ensayo_vs_muestra ?>', this.value, 'frm_grasa_peso_final', 'result_3', '<?= $grasa[0]->id_ensayo_vs_muestra ?>', 27)"
                                                <?= disable_frm($result_grasa[0]->result_3, session('user')->id) ?>>
                                                <label for="frm_grasa_peso_final">Peso final (g)</label>
                                                <span id="frm_grasa_peso_final"></span>
                                            </div>
                                            <div class="col s12 l2">
                                                <p><b>% grasa</b></p>
                                                <b id="campo_repuesta_mensaje_<?= $grasa[0]->id_ensayo_vs_muestra ?>"><?= $result_grasa[0]->result_5 ?> %</b>
                                            </div>
                                            <div class="input-field col s12 l3">
                                                <select name="frm_grasa_equipo"
                                                        id="frm_grasa_equipo"
                                                        onchange="js_cambiar_campos('campo_repuesta_equipo_<?= $grasa[0]->id_ensayo_vs_muestra?>',this.value, 'frm_grasa_equipo', 'id_equipo', '<?= $grasa[0]->id_ensayo_vs_muestra?>', 27)"
                                                        <?= disable_frm($result_grasa[0]->id_equipo, session('user')->usr_rol) ?>>
                                                    <option value="0">Seleccione equipo</option>
                                                    <?php foreach ($equipos as $key => $equipo): ?>
                                                        <option value="<?= $equipo->id_equipo ?>" <?= $equipo->id_equipo==$result_grasa[0]->id_equipo ?'selected':''; ?>><?= $equipo->nombre ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                                <label>Codigo equipo</label>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif ?>
                            <?php $grasa_g1 = fq_tiene_parametro($certificado->id_muestra_detalle, 131) ?>
                            <?php if (!empty($grasa_g1)): ?>
                                <?php $result_grasa_g1 = procesar_registro_fetch('ensa_mues_fq', 'id_ensayo_vs_muestra', $grasa_g1[0]->id_ensayo_vs_muestra, 'id_parametro', 131) ?>
                                <tr>
                                    <td>
                                        <p class="center-align"><b>Grasa Gerber</b></p>
                                        <div class="row">
                                            <div class="input-field col s12 l2">
                                                <input type="text" name="frm_grasa_gerber_1_peso_balon_vacio"
                                                id="frm_grasa_gerber_1_peso_balon_vacio" value="<?= $result_grasa_g1[0]->result_1 ?>"
                                                onblur="js_cambiar_campos('campo_repuesta_1_<?= $grasa_g1[0]->id_ensayo_vs_muestra ?>', this.value, 'frm_grasa_gerber_1_peso_balon_vacio', 'result_1', '<?= $grasa_g1[0]->id_ensayo_vs_muestra ?>', 131)"
                                                <?= disable_frm($result_grasa_g1[0]->result_1, session('user')->id) ?>>
                                                <label for="frm_grasa_gerber_1_peso_balon_vacio">Peso balón vacio (g)</label>
                                                <span id="frm_grasa_gerber_1_peso_balon_vacio"></span>
                                            </div>
                                            <div class="input-field col s12 l3">
                                                <input type="text" name="frm_grasa_gerber_1_peso_muestra_inicial"
                                                id="frm_grasa_gerber_1_peso_muestra_inicial" value="<?= $result_grasa_g1[0]->result_2 ?>"
                                                onblur="js_cambiar_campos('campo_repuesta_2_<?= $grasa_g1[0]->id_ensayo_vs_muestra ?>', this.value, 'frm_grasa_gerber_1_peso_muestra_inicial', 'result_2', '<?= $grasa_g1[0]->id_ensayo_vs_muestra ?>', 131)"
                                                <?= disable_frm($result_grasa_g1[0]->result_2, session('user')->id) ?>>
                                                <label for="frm_grasa_gerber_1_peso_muestra_inicial">Peso muestra inicial (g)</label>
                                                <span id="frm_grasa_gerber_1_peso_muestra_inicial"></span>
                                            </div>
                                            <div class="input-field col s12 l2">
                                                <input type="text" name="frm_grasa_gerber_1_peso_final"
                                                id="frm_grasa_gerber_1_peso_final" value="<?= $result_grasa_g1[0]->result_3 ?>"
                                                onblur="js_cambiar_campos('campo_repuesta_3_<?= $grasa_g1[0]->id_ensayo_vs_muestra ?>', this.value, 'frm_grasa_gerber_1_peso_final', 'result_3', '<?= $grasa_g1[0]->id_ensayo_vs_muestra ?>', 131)"
                                                <?= disable_frm($result_grasa_g1[0]->result_3, session('user')->id) ?>>
                                                <label for="frm_grasa_gerber_1_peso_final">Peso final (g)</label>
                                                <span id="frm_grasa_gerber_1_peso_final"></span>
                                            </div>
                                            <div class="col s12 l2">
                                                <p><b>% Grasa</b></p>
                                                <b id="campo_repuesta_mensaje_<?= $grasa_g1[0]->id_ensayo_vs_muestra ?>"><?= $result_grasa_g1[0]->result_5 ?> %</b>
                                            </div>
                                            <div class="input-field col s12 l3">
                                                <select name="frm_grasa_gerber_1_equipo"
                                                        id="frm_grasa_gerber_1_equipo"
                                                        onchange="js_cambiar_campos('campo_repuesta_equipo_<?= $grasa_g1[0]->id_ensayo_vs_muestra?>',this.value, 'frm_grasa_gerber_1_equipo', 'id_equipo', '<?= $grasa_g1[0]->id_ensayo_vs_muestra?>', 131)"
                                                        <?= disable_frm($result_grasa_g1[0]->id_equipo, session('user')->usr_rol) ?>>
                                                    <option value="0">Seleccione equipo</option>
                                                    <?php foreach ($equipos as $key => $equipo): ?>
                                                        <option value="<?= $equipo->id_equipo ?>" <?= $equipo->id_equipo==$result_grasa_g1[0]->id_equipo ?'selected':''; ?>><?= $equipo->nombre ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                                <label>Codigo equipo</label>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif ?>
                            <?php $grasa_g2 = fq_tiene_parametro($certificado->id_muestra_detalle, 188) ?>
                            <?php if (!empty($grasa_g2)): ?>
                                <?php $result_grasa_g2 = procesar_registro_fetch('ensa_mues_fq', 'id_ensayo_vs_muestra', $grasa_g2[0]->id_ensayo_vs_muestra, 'id_parametro', 188) ?>
                                <tr>
                                    <td>
                                        <p class="center-align"><b>Grasa Gerber</b></p>
                                        <div class="row">
                                            <div class="input-field col s12 l2">
                                                <input type="text" name="frm_grasa_gerber_1_peso_balon_vacio"
                                                id="frm_grasa_gerber_1_peso_balon_vacio" value="<?= $result_grasa_g2[0]->result_1 ?>"
                                                onblur="js_cambiar_campos('campo_repuesta_1_<?= $grasa_g2[0]->id_ensayo_vs_muestra ?>', this.value, 'frm_grasa_gerber_1_peso_balon_vacio', 'result_1', '<?= $grasa_g2[0]->id_ensayo_vs_muestra ?>', 188)"
                                                <?= disable_frm($result_grasa_g2[0]->result_1, session('user')->id) ?>>
                                                <label for="frm_grasa_gerber_1_peso_balon_vacio">Peso balón vacio (g)</label>
                                                <span id="frm_grasa_gerber_1_peso_balon_vacio"></span>
                                            </div>
                                            <div class="input-field col s12 l3">
                                                <input type="text" name="frm_grasa_gerber_1_peso_muestra_inicial"
                                                id="frm_grasa_gerber_1_peso_muestra_inicial" value="<?= $result_grasa_g2[0]->result_2 ?>"
                                                onblur="js_cambiar_campos('campo_repuesta_2_<?= $grasa_g2[0]->id_ensayo_vs_muestra ?>', this.value, 'frm_grasa_gerber_1_peso_muestra_inicial', 'result_2', '<?= $grasa_g2[0]->id_ensayo_vs_muestra ?>', 188)"
                                                <?= disable_frm($result_grasa_g2[0]->result_2, session('user')->id) ?>>
                                                <label for="frm_grasa_gerber_1_peso_muestra_inicial">Peso muestra inicial (g)</label>
                                                <span id="frm_grasa_gerber_1_peso_muestra_inicial"></span>
                                            </div>
                                            <div class="input-field col s12 l2">
                                                <input type="text" name="frm_grasa_gerber_1_peso_final"
                                                id="frm_grasa_gerber_1_peso_final" value="<?= $result_grasa_g2[0]->result_3 ?>"
                                                onblur="js_cambiar_campos('campo_repuesta_3_<?= $grasa_g2[0]->id_ensayo_vs_muestra ?>', this.value, 'frm_grasa_gerber_1_peso_final', 'result_3', '<?= $grasa_g2[0]->id_ensayo_vs_muestra ?>', 188)"
                                                <?= disable_frm($result_grasa_g2[0]->result_3, session('user')->id) ?>>
                                                <label for="frm_grasa_gerber_1_peso_final">Peso final (g)</label>
                                                <span id="frm_grasa_gerber_1_peso_final"></span>
                                            </div>
                                            <div class="col s12 l2">
                                                <p><b>% Grasa</b></p>
                                                <b id="campo_repuesta_mensaje_<?= $grasa_g2[0]->id_ensayo_vs_muestra ?>"><?= $result_grasa_g1[0]->result_5 ?> %</b>
                                            </div>
                                            <div class="input-field col s12 l3">
                                                <select name="frm_grasa_gerber_1_equipo"
                                                        id="frm_grasa_gerber_1_equipo"
                                                        onchange="js_cambiar_campos('campo_repuesta_equipo_<?= $grasa_g2[0]->id_ensayo_vs_muestra?>',this.value, 'frm_grasa_gerber_1_equipo', 'id_equipo', '<?= $grasa_g2[0]->id_ensayo_vs_muestra?>', 188)"
                                                        <?= disable_frm($result_grasa_g1[0]->id_equipo, session('user')->usr_rol) ?>>
                                                    <option value="0">Seleccione equipo</option>
                                                    <?php foreach ($equipos as $key => $equipo): ?>
                                                        <option value="<?= $equipo->id_equipo ?>" <?= $equipo->id_equipo==$result_grasa_g2[0]->id_equipo ?'selected':''; ?>><?= $equipo->nombre ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                                <label>Codigo equipo</label>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif ?>
                            <?php $ceniza = fq_tiene_parametro($certificado->id_muestra_detalle, 29) ?>
                            <?php if (!empty($ceniza)): ?>
                                <?php $result_ceniza = procesar_registro_fetch('ensa_mues_fq', 'id_ensayo_vs_muestra', $ceniza[0]->id_ensayo_vs_muestra, 'id_parametro', 29) ?>
                                <tr>
                                    <td>
                                        <p class="center-align"><b>Cenizas</b></p>
                                        <div class="row">
                                            <div class="input-field col s12 l2">
                                                <input type="text" name="frm_ceniza_peso_vacio"
                                                id="frm_ceniza_peso_vacio" value="<?= $result_ceniza[0]->result_1 ?>"
                                                onblur="js_cambiar_campos('campo_repuesta_1_<?= $ceniza[0]->id_ensayo_vs_muestra ?>', this.value, 'frm_ceniza_peso_vacio', 'result_1', '<?= $ceniza[0]->id_ensayo_vs_muestra ?>', 29)"
                                                <?= disable_frm($result_ceniza[0]->result_1, session('user')->id) ?>>
                                                <label for="frm_ceniza_peso_vacio">Peso vacio (g)</label>
                                                <span id="frm_ceniza_peso_vacio"></span>
                                            </div>
                                            <div class="input-field col s12 l3">
                                                <input type="text" name="frm_ceniza_peso_muestra"
                                                id="frm_ceniza_peso_muestra" value="<?= $result_ceniza[0]->result_2 ?>"
                                                onblur="js_cambiar_campos('campo_repuesta_2_<?= $ceniza[0]->id_ensayo_vs_muestra ?>', this.value, 'frm_ceniza_peso_muestra', 'result_2', '<?= $ceniza[0]->id_ensayo_vs_muestra ?>', 29)"
                                                <?= disable_frm($result_ceniza[0]->result_2, session('user')->id) ?>>
                                                <label for="frm_ceniza_peso_muestra">Peso de la muestra (g)</label>
                                                <span id="frm_ceniza_peso_muestra"></span>
                                            </div>
                                            <div class="input-field col s12 l2">
                                                <input type="text" name="frm_ceniza_peso_ceniza"
                                                id="frm_ceniza_peso_ceniza" value="<?= $result_ceniza[0]->result_3 ?>"
                                                onblur="js_cambiar_campos('campo_repuesta_3_<?= $ceniza[0]->id_ensayo_vs_muestra ?>', this.value, 'frm_ceniza_peso_ceniza', 'result_3', '<?= $ceniza[0]->id_ensayo_vs_muestra ?>', 29)"
                                                <?= disable_frm($result_ceniza[0]->result_3, session('user')->id) ?>>
                                                <label for="frm_ceniza_peso_ceniza">Peso cenizas (g)</label>
                                                <span id="frm_ceniza_peso_ceniza"></span>
                                            </div>
                                            <div class="col s12 l2">
                                                <p><b>% Cenizas</b></p>
                                                <b id="campo_repuesta_mensaje_<?= $ceniza[0]->id_ensayo_vs_muestra ?>"><?= $result_ceniza[0]->result_5 ?> %</b>
                                            </div>
                                            <div class="input-field col s12 l3">
                                                <select name="frm_ceniza_equipo"
                                                        id="frm_ceniza_equipo"
                                                        onchange="js_cambiar_campos('campo_repuesta_equipo_<?= $ceniza[0]->id_ensayo_vs_muestra?>',this.value, 'frm_ceniza_equipo', 'id_equipo', '<?= $ceniza[0]->id_ensayo_vs_muestra?>', 29)"
                                                        <?= disable_frm($result_ceniza[0]->id_equipo, session('user')->usr_rol) ?>>
                                                    <option value="0">Seleccione equipo</option>
                                                    <?php foreach ($equipos as $key => $equipo): ?>
                                                        <option value="<?= $equipo->id_equipo ?>" <?= $equipo->id_equipo==$result_ceniza[0]->id_equipo ?'selected':''; ?>><?= $equipo->nombre ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                                <label>Codigo equipo</label>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif ?>
                            <?php $acidez = fq_tiene_parametro($certificado->id_muestra_detalle, 151) ?>
                            <?php if (!empty($acidez[0])): ?>
                                <?php $result_acidez = procesar_registro_fetch('ensa_mues_fq', 'id_ensayo_vs_muestra', $acidez[0]->id_ensayo_vs_muestra, 'id_parametro', 151) ?>
                                <tr>
                                    <td>
                                        <p class="center-align">
                                            <b>% ACIDEZ TITULABLE Total</b>
                                            <br>
                                            <small>( VNaOH x NNAOH x Factor  x 100 ) / Peso muestra </small>
                                        </p>
                                        <div class="row">
                                            <div class="input-field col s12 l4">
                                                <input type="text" name="frm_acidez_cantidad"
                                                id="frm_acidez_cantidad" value="<?= $result_acidez[0]->result_1 ?>"
                                                onblur="js_cambiar_campos('campo_repuesta_1_<?= $acidez[0]->id_ensayo_vs_muestra ?>', this.value, 'frm_acidez_cantidad', 'result_1', '<?= $acidez[0]->id_ensayo_vs_muestra ?>', 151)"
                                                <?= disable_frm($result_acidez[0]->result_1, session('user')->id) ?>>
                                                <label for="frm_acidez_cantidad">Peso muestra</label>
                                                <span id="frm_acidez_cantidad"></span>
                                            </div>
                                            <div class="input-field col s12 l4">
                                                <input type="text" name="frm_acidez_concentracion_NaH"
                                                id="frm_acidez_concentracion_NaH" value="<?= $result_acidez[0]->result_2 ?>"
                                                onblur="js_cambiar_campos('campo_repuesta_2_<?= $acidez[0]->id_ensayo_vs_muestra ?>', this.value, 'frm_acidez_concentracion_NaH', 'result_2', '<?= $acidez[0]->id_ensayo_vs_muestra ?>', 151)"
                                                <?= disable_frm($result_acidez[0]->result_2, session('user')->id) ?>>
                                                <label for="frm_acidez_concentracion_NaH">VNaOH</label>
                                                <span id="frm_acidez_concentracion_NaH"></span>
                                            </div>
                                            <div class="input-field col s12 l4">
                                                <input type="text" name="frm_acidez_vol_gastado_NaOH"
                                                id="frm_acidez_vol_gastado_NaOH" value="<?= $result_acidez[0]->result_3 ?>"
                                                onblur="js_cambiar_campos('campo_repuesta_3_<?= $acidez[0]->id_ensayo_vs_muestra ?>', this.value, 'frm_acidez_vol_gastado_NaOH', 'result_3', '<?= $acidez[0]->id_ensayo_vs_muestra ?>', 151)"
                                                <?= disable_frm($result_acidez[0]->result_3, session('user')->id) ?>>
                                                <label for="frm_acidez_vol_gastado_NaOH">NNAOH</label>
                                                <span id="frm_acidez_vol_gastado_NaOH"></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-field col s12 l4">
                                                <select name="frm_acidez_factor"
                                                        id="frm_acidez_factor"
                                                        onchange="js_cambiar_campos('campo_repuesta_factor_<?= $acidez[0]->id_ensayo_vs_muestra?>',this.value, 'frm_acidez_factor', 'id_factor', '<?= $acidez[0]->id_ensayo_vs_muestra?>', 151)"
                                                        <?= disable_frm($result_acidez[0]->id_factor, session('user')->usr_rol) ?>>
                                                    <option value="0">Seleccione equipo</option>
                                                    <?php foreach ($factores as $key => $factor): ?>
                                                        <option value="<?= $factor->id_factor ?>" <?= $factor->id_factor==$result_acidez[0]->id_factor ?'selected':''; ?>><?= $factor->nombre ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                                <label>Factor ácido equival.</label>
                                            </div>
                                            <div class="col s12 l4">
                                                <p><b>% Acidez</b></p>
                                                <b id="campo_repuesta_mensaje_<?= $acidez[0]->id_ensayo_vs_muestra ?>"><?= $result_acidez[0]->result_5 ?> %</b>
                                            </div>
                                            <div class="input-field col s12 l4">
                                                <select name="frm_acidez_equipo"
                                                        id="frm_acidez_equipo"
                                                        onchange="js_cambiar_campos('campo_repuesta_equipo_<?= $acidez[0]->id_ensayo_vs_muestra?>',this.value, 'frm_acidez_equipo', 'id_equipo', '<?= $acidez[0]->id_ensayo_vs_muestra?>', 151)"
                                                        <?= disable_frm($result_acidez[0]->id_equipo, session('user')->usr_rol) ?>>
                                                    <option value="0">Seleccione equipo</option>
                                                    <?php foreach ($equipos as $key => $equipo): ?>
                                                        <option value="<?= $equipo->id_equipo ?>" <?= $equipo->id_equipo==$result_acidez[0]->id_equipo ?'selected':''; ?>><?= $equipo->nombre ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                                <label>Codigo equipo</label>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif ?>
                            <?php $acidez_2 = fq_tiene_parametro($certificado->id_muestra_detalle, 157) ?>
                            <?php if (!empty($acidez_2[0])): ?>
                                <?php $acidez = $acidez_2 ?>
                                <?php $result_acidez = procesar_registro_fetch('ensa_mues_fq', 'id_ensayo_vs_muestra', $acidez_2[0]->id_ensayo_vs_muestra, 'id_parametro', 157) ?>
                                <tr>
                                    <td>
                                        <p class="center-align">
                                            <b>% ACIDEZ TITULABLE Nectares</b>
                                            <br>
                                            <small>( VNaOH x NNAOH x Factor  x 100 ) / Peso muestra </small>
                                        </p>
                                        <div class="row">
                                            <div class="input-field col s12 l4">
                                                <input type="text" name="frm_acidez_cantidad"
                                                id="frm_acidez_cantidad" value="<?= $result_acidez[0]->result_1 ?>"
                                                onblur="js_cambiar_campos('campo_repuesta_1_<?= $acidez[0]->id_ensayo_vs_muestra ?>', this.value, 'frm_acidez_cantidad', 'result_1', '<?= $acidez[0]->id_ensayo_vs_muestra ?>', 157)"
                                                <?= disable_frm($result_acidez[0]->result_1, session('user')->id) ?>>
                                                <label for="frm_acidez_cantidad">Peso muestra</label>
                                                <span id="frm_acidez_cantidad"></span>
                                            </div>
                                            <div class="input-field col s12 l4">
                                                <input type="text" name="frm_acidez_concentracion_NaH"
                                                id="frm_acidez_concentracion_NaH" value="<?= $result_acidez[0]->result_2 ?>"
                                                onblur="js_cambiar_campos('campo_repuesta_2_<?= $acidez[0]->id_ensayo_vs_muestra ?>', this.value, 'frm_acidez_concentracion_NaH', 'result_2', '<?= $acidez[0]->id_ensayo_vs_muestra ?>', 157)"
                                                <?= disable_frm($result_acidez[0]->result_2, session('user')->id) ?>>
                                                <label for="frm_acidez_concentracion_NaH">VNaOH</label>
                                                <span id="frm_acidez_concentracion_NaH"></span>
                                            </div>
                                            <div class="input-field col s12 l4">
                                                <input type="text" name="frm_acidez_vol_gastado_NaOH"
                                                id="frm_acidez_vol_gastado_NaOH" value="<?= $result_acidez[0]->result_3 ?>"
                                                onblur="js_cambiar_campos('campo_repuesta_3_<?= $acidez[0]->id_ensayo_vs_muestra ?>', this.value, 'frm_acidez_vol_gastado_NaOH', 'result_3', '<?= $acidez[0]->id_ensayo_vs_muestra ?>', 157)"
                                                <?= disable_frm($result_acidez[0]->result_3, session('user')->id) ?>>
                                                <label for="frm_acidez_vol_gastado_NaOH">NNAOH</label>
                                                <span id="frm_acidez_vol_gastado_NaOH"></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-field col s12 l4">
                                                <select name="frm_acidez_factor"
                                                        id="frm_acidez_factor"
                                                        onchange="js_cambiar_campos('campo_repuesta_factor_<?= $acidez[0]->id_ensayo_vs_muestra?>',this.value, 'frm_acidez_factor', 'id_factor', '<?= $acidez[0]->id_ensayo_vs_muestra?>', 151)"
                                                        <?= disable_frm($result_acidez[0]->id_factor, session('user')->usr_rol) ?>>
                                                    <option value="0">Seleccione equipo</option>
                                                    <?php foreach ($factores as $key => $factor): ?>
                                                        <option value="<?= $factor->id_factor ?>" <?= $factor->id_factor==$result_acidez[0]->id_factor ?'selected':''; ?>><?= $factor->nombre ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                                <label>Factor ácido equival.</label>
                                            </div>
                                            <div class="col s12 l4">
                                                <p><b>% Acidez</b></p>
                                                <b id="campo_repuesta_mensaje_<?= $acidez[0]->id_ensayo_vs_muestra ?>"><?= $result_acidez[0]->result_5 ?> %</b>
                                            </div>
                                            <div class="input-field col s12 l4">
                                                <select name="frm_ceniza_equipo"
                                                        id="frm_ceniza_equipo"
                                                        onchange="js_cambiar_campos('campo_repuesta_equipo_<?= $acidez[0]->id_ensayo_vs_muestra?>',this.value, 'frm_ceniza_equipo', 'id_equipo', '<?= $acidez[0]->id_ensayo_vs_muestra?>', 29)"
                                                        <?= disable_frm($result_acidez[0]->id_equipo, session('user')->usr_rol) ?>>
                                                    <option value="0">Seleccione equipo</option>
                                                    <?php foreach ($equipos as $key => $equipo): ?>
                                                        <option value="<?= $equipo->id_equipo ?>" <?= $equipo->id_equipo==$result_acidez[0]->id_equipo ?'selected':''; ?>><?= $equipo->nombre ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                                <label>Codigo equipo</label>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif ?>
                            <?php $base_v = fq_tiene_parametro($certificado->id_muestra_detalle, 120) ?>
                            <?php if (!empty($base_v[0])): ?>
                                <?php $result_base_v = procesar_registro_fetch('ensa_mues_fq', 'id_ensayo_vs_muestra', $base_v[0]->id_ensayo_vs_muestra, 'id_parametro', 120) ?>
                                <tr>
                                    <td>
                                        <p class="center-align">
                                            <b>Bases volatiles Nitrogenadas.</b>
                                            <br>
                                            <small>( VHCl  x NHCl x (factor 14)  x 100 ) / Peso muestra </small>
                                        </p>
                                        <div class="row">
                                            <div class="input-field col s12 l4">
                                                <input type="text" name="frm_base_v_cantidad"
                                                id="frm_base_v_cantidad" value="<?= $result_base_v[0]->result_1 ?>"
                                                onblur="js_cambiar_campos('campo_repuesta_1_<?= $base_v[0]->id_ensayo_vs_muestra ?>', this.value, 'frm_base_v_cantidad', 'result_1', '<?= $base_v[0]->id_ensayo_vs_muestra ?>', 120)"
                                                <?= disable_frm($result_base_v[0]->result_1, session('user')->id) ?>>
                                                <label for="frm_base_v_cantidad">Peso muestra</label>
                                                <span id="frm_base_v_cantidad"></span>
                                            </div>
                                            <div class="input-field col s12 l4">
                                                <input type="text" name="frm_base_v_VHC"
                                                id="frm_base_v_VHC" value="<?= $result_base_v[0]->result_2 ?>"
                                                onblur="js_cambiar_campos('campo_repuesta_2_<?= $base_v[0]->id_ensayo_vs_muestra ?>', this.value, 'frm_base_v_VHC', 'result_2', '<?= $base_v[0]->id_ensayo_vs_muestra ?>', 120)"
                                                <?= disable_frm($result_base_v[0]->result_2, session('user')->id) ?>>
                                                <label for="frm_base_v_VHC">VHCl</label>
                                                <span id="frm_base_v_VHC"></span>
                                            </div>
                                            <div class="input-field col s12 l4">
                                                <input type="text" name="frm_base_v_NHC"
                                                id="frm_base_v_NHC" value="<?= $result_base_v[0]->result_3 ?>"
                                                onblur="js_cambiar_campos('campo_repuesta_3_<?= $base_v[0]->id_ensayo_vs_muestra ?>', this.value, 'frm_base_v_NHC', 'result_3', '<?= $base_v[0]->id_ensayo_vs_muestra ?>', 120)"
                                                <?= disable_frm($result_base_v[0]->result_3, session('user')->id) ?>>
                                                <label for="frm_base_v_NHC">NHCl</label>
                                                <span id="frm_base_v_NHC"></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-field col s12 l4">
                                                <select name="frm_base_v_factor"
                                                        id="frm_base_v_factor"
                                                        onchange="js_cambiar_campos('campo_repuesta_factor_<?= $base_v[0]->id_ensayo_vs_muestra?>',this.value, 'frm_base_v_factor', 'id_factor', '<?= $base_v[0]->id_ensayo_vs_muestra?>', 120)"
                                                        <?= disable_frm($result_base_v[0]->id_factor, session('user')->usr_rol) ?>>
                                                    <option value="0">Seleccione equipo</option>
                                                    <?php foreach ($factores as $key => $factor): ?>
                                                        <option value="<?= $factor->id_factor ?>" <?= $factor->id_factor==$result_base_v[0]->id_factor ?'selected':''; ?>><?= $factor->nombre ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                                <label>Factor ácido equival.</label>
                                            </div>
                                            <div class="col s12 l4">
                                                <p><b>BASES VOLÁTILES</b></p>
                                                <b id="campo_repuesta_mensaje_<?= $base_v[0]->id_ensayo_vs_muestra ?>"><?= $result_base_v[0]->result_5 ?> %</b>
                                            </div>
                                            <div class="input-field col s12 l4">
                                                <select name="frm_base_v_equipo"
                                                        id="frm_base_v_equipo"
                                                        onchange="js_cambiar_campos('campo_repuesta_equipo_<?= $base_v[0]->id_ensayo_vs_muestra?>',this.value, 'frm_base_v_equipo', 'id_equipo', '<?= $base_v[0]->id_ensayo_vs_muestra?>', 120)"
                                                        <?= disable_frm($result_base_v[0]->id_equipo, session('user')->usr_rol) ?>>
                                                    <option value="0">Seleccione equipo</option>
                                                    <?php foreach ($equipos as $key => $equipo): ?>
                                                        <option value="<?= $equipo->id_equipo ?>" <?= $equipo->id_equipo==$result_base_v[0]->id_equipo ?'selected':''; ?>><?= $equipo->nombre ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                                <label>Codigo equipo</label>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif ?>
                            <?php $base_v = fq_tiene_parametro($certificado->id_muestra_detalle, 111) ?>
                            <?php if (!empty($base_v[0])): ?>
                                <?php $result_base_v = procesar_registro_fetch('ensa_mues_fq', 'id_ensayo_vs_muestra', $base_v[0]->id_ensayo_vs_muestra, 'id_parametro', 111) ?>
                                <tr>
                                    <td>
                                        <p class="center-align">
                                            <b>Bases volatiles Nitrogenadas.</b>
                                            <br>
                                            <small>( VHCl  x NHCl x (factor 14)  x 100 ) / Peso muestra </small>
                                        </p>
                                        <div class="row">
                                            <div class="input-field col s12 l4">
                                                <input type="text" name="frm_base_v_cantidad"
                                                id="frm_base_v_cantidad" value="<?= $result_base_v[0]->result_1 ?>"
                                                onblur="js_cambiar_campos('campo_repuesta_1_<?= $base_v[0]->id_ensayo_vs_muestra ?>', this.value, 'frm_base_v_cantidad', 'result_1', '<?= $base_v[0]->id_ensayo_vs_muestra ?>', 111)"
                                                <?= disable_frm($result_base_v[0]->result_1, session('user')->id) ?>>
                                                <label for="frm_base_v_cantidad">Peso muestra</label>
                                                <span id="frm_base_v_cantidad"></span>
                                            </div>
                                            <div class="input-field col s12 l4">
                                                <input type="text" name="frm_base_v_VHC"
                                                id="frm_base_v_VHC" value="<?= $result_base_v[0]->result_2 ?>"
                                                onblur="js_cambiar_campos('campo_repuesta_2_<?= $base_v[0]->id_ensayo_vs_muestra ?>', this.value, 'frm_base_v_VHC', 'result_2', '<?= $base_v[0]->id_ensayo_vs_muestra ?>', 111)"
                                                <?= disable_frm($result_base_v[0]->result_2, session('user')->id) ?>>
                                                <label for="frm_base_v_VHC">VHCl</label>
                                                <span id="frm_base_v_VHC"></span>
                                            </div>
                                            <div class="input-field col s12 l4">
                                                <input type="text" name="frm_base_v_NHC"
                                                id="frm_base_v_NHC" value="<?= $result_base_v[0]->result_3 ?>"
                                                onblur="js_cambiar_campos('campo_repuesta_3_<?= $base_v[0]->id_ensayo_vs_muestra ?>', this.value, 'frm_base_v_NHC', 'result_3', '<?= $base_v[0]->id_ensayo_vs_muestra ?>', 111)"
                                                <?= disable_frm($result_base_v[0]->result_3, session('user')->id) ?>>
                                                <label for="frm_base_v_NHC">NHCl</label>
                                                <span id="frm_base_v_NHC"></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-field col s12 l4">
                                                <select name="frm_base_v_factor"
                                                        id="frm_base_v_factor"
                                                        onchange="js_cambiar_campos('campo_repuesta_factor_<?= $base_v[0]->id_ensayo_vs_muestra?>',this.value, 'frm_base_v_factor', 'id_factor', '<?= $base_v[0]->id_ensayo_vs_muestra?>', 111)"
                                                        <?= disable_frm($result_base_v[0]->id_factor, session('user')->usr_rol) ?>>
                                                    <option value="0">Seleccione equipo</option>
                                                    <?php foreach ($factores as $key => $factor): ?>
                                                        <option value="<?= $factor->id_factor ?>" <?= $factor->id_factor==$result_base_v[0]->id_factor ?'selected':''; ?>><?= $factor->nombre ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                                <label>Factor ácido equival.</label>
                                            </div>
                                            <div class="col s12 l4">
                                                <p><b>BASES VOLÁTILES</b></p>
                                                <b id="campo_repuesta_mensaje_<?= $base_v[0]->id_ensayo_vs_muestra ?>"><?= $result_base_v[0]->result_5 ?> %</b>
                                            </div>
                                            <div class="input-field col s12 l4">
                                                <select name="frm_base_v_equipo"
                                                        id="frm_base_v_equipo"
                                                        onchange="js_cambiar_campos('campo_repuesta_equipo_<?= $base_v[0]->id_ensayo_vs_muestra?>',this.value, 'frm_base_v_equipo', 'id_equipo', '<?= $base_v[0]->id_ensayo_vs_muestra?>', 111)"
                                                        <?= disable_frm($result_base_v[0]->id_equipo, session('user')->usr_rol) ?>>
                                                    <option value="0">Seleccione equipo</option>
                                                    <?php foreach ($equipos as $key => $equipo): ?>
                                                        <option value="<?= $equipo->id_equipo ?>" <?= $equipo->id_equipo==$result_base_v[0]->id_equipo ?'selected':''; ?>><?= $equipo->nombre ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                                <label>Codigo equipo</label>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif ?>
                            <?php $carbohidratos = fq_tiene_parametro($certificado->id_muestra_detalle, 28) ?>
                            <?php if (!empty($carbohidratos[0])): ?>
                                <?php $result_carbohidratos = procesar_registro_fetch('ensayo_vs_muestra', 'id_ensayo_vs_muestra', $carbohidratos[0]->id_ensayo_vs_muestra) ?>
                                <tr>
                                    <td>
                                        <p class="center-align">
                                            <b>CARBOHIDRATOS</b>
                                        </p>
                                        <div class="row">
                                            <div class="input-field col s12 l6">
                                                <select id="campo_muestra_redondeo_carbohidratos" name="campo_muestra_redondeo_carbohidratos"
                                                    onchange="js_calcula_independiente('<?= $carbohidratos[0]->id_ensayo_vs_muestra ?>', 'carbohidratos', this.value, 'calcula_carbohidratos')">
                                                    <option>Sin seleccionar</option>
                                                    <option value="0">Número entero</option>
                                                    <option value="1">Número con 1 decimal</option>
                                                    <option value="2">Número con 2 decimales</option>
                                                    <option value="2">Número con 3 decimales</option>
                                                </select>
                                                <label for="campo_muestra_redondeo_carbohidratos">Seleccione cifra de redondeo :</label>
                                            </div>
                                            <div class="col s12 l6 campo_resultado_carbohidratos">
                                                <p>% CARBOHIDRATOS</p>
                                                <b><?= $result_carbohidratos[0]->resultado_mensaje ? $result_carbohidratos[0]->resultado_mensaje.' %' : 'Sin resultado' ?></b>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif ?>
                            <?php $calorias = fq_tiene_parametro($certificado->id_muestra_detalle, 30) ?>
                            <?php if (!empty($calorias[0])): ?>
                                <?php $result_calorias = procesar_registro_fetch('ensayo_vs_muestra', 'id_ensayo_vs_muestra', $calorias[0]->id_ensayo_vs_muestra) ?>
                                <tr>
                                    <td>
                                        <p class="center-align">
                                            <b>CALORIAS</b>
                                        </p>
                                        <div class="row">
                                            <div class="input-field col s12 l6">
                                                <select id="campo_muestra_redondeo_calorias" name="campo_muestra_redondeo_calorias"
                                                    onchange="js_calcula_independiente('<?= $calorias[0]->id_ensayo_vs_muestra ?>', 'calorias', this.value, 'calcula_calorias')">
                                                    <option>Sin seleccionar</option>
                                                    <option value="0">Número entero</option>
                                                    <option value="1">Número con 1 decimal</option>
                                                    <option value="2">Número con 2 decimales</option>
                                                    <option value="2">Número con 3 decimales</option>
                                                </select>
                                                <label for="campo_muestra_redondeo_calorias">Seleccione cifra de redondeo :</label>
                                            </div>
                                            <div class="col s12 l6 campo_resultado_calorias">
                                                <p>% CALORIAS</p>
                                                <b><?= $result_calorias[0]->resultado_mensaje ? $result_calorias[0]->resultado_mensaje.' %' : 'Sin resultado' ?></b>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif ?>
                            <?php if (!empty($otros)): ?>
                                <tr>
                                    <td>
                                        <p class="center-align">
                                            <b>OTROS ANALISIS</b>
                                        </p>
                                        <?php $llave = 6 ?>
                                        <?php foreach ($otros as $key => $fila): ?>
                                            <?php if ($llave == 6): ?>
                                                <div class="row">
                                                    <?php $llave = 1 ?>
                                            <?php else: ?>
                                                <?php $llave++ ?>
                                            <?php endif ?>
                                            <div class="input-field col s12 l2">
                                                <input type="text" name="frm_otro<?= $key ?>" id="frm_otro<?= $key ?>"
                                                value="<?= $fila->resultado_mensaje ?>"
                                                onblur="js_cambiar_campos('campo_repuesta_otro_<?= $fila->id_ensayo_vs_muestra ?>', this.value, 'frm_otro<?= $key ?>', 'result_3', '<?= $fila->id_ensayo_vs_muestra ?>', <?= $fila->id_parametro ?>)"
                                                <?= disable_frm($fila->resultado_mensaje, session('user')->id) ?>>
                                                <label for="frm_otro<?= $key ?>"><?= $fila->par_nombre ?></label>
                                                <span id="campo_repuesta_otro_<?= $fila->id_ensayo_vs_muestra ?>"></span>
                                            </div>
                                            <?php if ($llave == 6): ?>
                                                </div>
                                            <?php endif ?>
                                        <?php endforeach ?>
                                    </td>
                                </tr>
                            <?php endif ?>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    <?php else: ?>
        <?php if (empty($validate)): ?>
            <h5 class="center-align">No se encontr&oacute; ningun resultado de FA o FM </h5>
            <hr>
        <?php endif ?>
    <?php endif ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?= view('layouts/footer') ?>
<script src="<?= base_url(['assets', 'js', 'funcionarios', 'funciones.js']) ?>"></script>
<script src="<?= base_url(['assets', 'js', 'funcionarios', 'resultadosALFQ.js']) ?>"></script>

