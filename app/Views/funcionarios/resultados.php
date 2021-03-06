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
                                        Registro de muestras
                                    </h2>
                                    <hr>
<div class="row">
    <form class="col s12" method="POST" autocomplete="off" action="<?= base_url(['funcionario', 'resultados', 'ingreso']) ?>">
        <div class="row">
            <div class="input-field col s12 l3">
                <input id="frm_codigo_busca" name="frm_codigo_busca" type="text" class="validate">
                <label for="frm_codigo_busca">Código Amc</label>
            </div>
            <div class="input-field col s12 l3">
                <input id="frm_dia_listar" name="frm_dia_listar" type="text" class="validate">
                <label for="frm_dia_listar">Dia</label>
            </div>
            <div class="input-field col s12 l3">
                <select name="frm_tipo_analisis">
                    <option value="123456">Todos</option>
                    <?php foreach ($analisis as $key => $value): ?>
                        <option value="<?= $value->id_muestra_tipo_analsis ?>"><?= $value->mue_sigla ?></option>
                    <?php endforeach ?>
                </select>
                <label>Tipo analisis</label>
            </div>
            <div class="input-field col s12 l12 centrar_button">
                <button class="btn gradient-45deg-purple-deep-orange border-round" id="btn-buscar-muestra">
                    <i class="fas fa-search"></i> Buscar
                </button>
            </div>
        </div>
    </form>
</div>
<?php if (!empty($muestras)): ?>
    <hr>
    <ul class="collapsible expandable popout">
        <?php foreach ($muestras as $llave => $muestra): ?>
            <?php $aux_codigo_amc = construye_codigo_amc($muestra->id_muestra_detalle); ?>
            <li class="<?= $llave == 0 ? 'active':'' ?>">
                <div class="collapsible-header">
                    <div class="list-muestras">
                        <div class="col s1 l1">
                            <span><b><?= $llave+1 ?></b></span>
                        </div>
                        <div class="col s11 l3">
                            <span><b>Codigo:</b> <?= $aux_codigo_amc?></span>
                        </div>
                        <div class="col s12 l6">
                            <span><b>Identificación:</b> <?= $muestra->mue_identificacion ?></span>
                        </div>
                        <div class="col s12 l2">
                            <span><b><?= $muestra->certificado_nro ?></b></span>
                        </div>
                    </div>
                </div>
                <div class="collapsible-body">
                    <div class="mensaje_algo mensaje_<?= $muestra->id_muestreo ?>"></div>
                    <div class="table-content">
                        <form action="<?= base_url(['funcionario','resultados','ingreso','resultado']) ?>" id="form_resultado" autocomplete="off">
                        <table class="centered striped highlight">
                            <thead>
                                <tr>
                                    <?php foreach($muestra->parametros as $key => $parametro): ?>
                                        <th><?= $parametro->parametro ?><br>(<?= $parametro->tecnica ?>)</th>
                                    <?php endforeach ?>
                                </tr>
                            </thead>

    <tbody>
        <tr>
            <?php foreach($muestra->ensayo_vs_muestras as $key => $ensayo_vs_muestra): ?>
                        <?php
                            $aux_id_ensayo_vs_muestra = $ensayo_vs_muestra->id_ensayo_vs_muestra;
                            $aux_cambiar_campos = 'onblur="js_cambiar_campos(`campo_repuesta_'.$aux_id_ensayo_vs_muestra.'`,this.value, `frm_resultado'.$aux_id_ensayo_vs_muestra.'`, `resultado_analisis`, '.$aux_id_ensayo_vs_muestra.', '.$muestra->parametros[$key]->id_tecnica.', '.$muestra->id_tipo_analisis.')"' ;
                            $aux_mensaje_tecnica_7_y_texto = ($muestra->parametros[$key]->id_tecnica==7 || preg_match('/usen/',$muestra->ensayos[$key]->med_valor_min) || preg_match('/usen/',$muestra->ensayos[$key]->med_valor_max) )?formatea_valor_min_max($muestra->ensayos[$key]->med_valor_min).'-'.formatea_valor_min_max($aux_fila_ensayo[$key]->med_valor_max) :'';
                            $aux_mensaje_tip = 'onclick="js_muestra_tip('.$muestra->id_muestreo.', `'.$aux_codigo_amc.'`, `'.$muestra->parametros[$key]->parametro.'`, `'.$muestra->mue_identificacion.'`, `'.$muestra->pro_nombre.'`)" ' ;
                            $aux_cambiar_campos2 = 'onblur="js_cambiar_campos(`campo_repuesta_'.$aux_id_ensayo_vs_muestra.'\'`,this.value, `frm_resultado2'.$aux_id_ensayo_vs_muestra.'`, `resultado_analisis2`, '.$aux_id_ensayo_vs_muestra.', '.$parametro->id_tecnica.','.$muestra->id_tipo_analisis.')" ' ;
                            if($ensayo_vs_muestra->resultado_analisis2){
                                $aux_cambiar_campos2 = (session('user')->usr_rol == 1 || session('user')->usr_rol == 2 || session('user')->usr_rol == 3) ? $aux_cambiar_campos2.' class="valid" ':'disabled class="valid"  ';//|| $user_rol_id==4
                            }
                            $aux_value2 = $ensayo_vs_muestra->resultado_analisis2;
                            $aux_input2 = '<input type="text" name="frm_resultado2'.$aux_id_ensayo_vs_muestra.'" id="frm_resultado2'.$aux_id_ensayo_vs_muestra.'" '.$aux_cambiar_campos2.' value="'.$aux_value2.'">';
                            if(!$ensayo_vs_muestra->resultado_analisis  ){// || 
                                if($ensayo_vs_muestra->resultado_analisis == '0' ){
                                    $aux_cambiar_campos = (session('user')->usr_rol == 1 || session('user')->usr_rol == 2 || session('user')->usr_rol == 3) ? $aux_cambiar_campos.' class="valid"':'disabled class="valid"';
                                    $aux_value          =   $ensayo_vs_muestra->resultado_analisis;
                                }else{
                                    $aux_value          =   '';
                                    $aux_input2         = '';
                                }                                                
                            }else{
                                $aux_cambiar_campos = (session('user')->usr_rol == 1 || session('user')->usr_rol == 2 || session('user')->usr_rol == 3) ? $aux_cambiar_campos.' class="valid"  ':'disabled class="valid"  ';
                                $aux_value = $ensayo_vs_muestra->resultado_analisis;
                            }
                            $aux_div_rta = '<div id="campo_respuesta_'.$aux_id_ensayo_vs_muestra.'"></div>';
                            $aux_mensaje_respuesta='';
                            if(isset($ensayo_vs_muestra->resultado_mensaje) ){
                               if($ensayo_vs_muestra->resultado_mensaje<>'' ){
                                   if($ensayo_vs_muestra->resultado_analisis2=='' ){
                                        $aux_input2='';   
                                   }                                               
                                   $aux_mensaje_respuesta=$ensayo_vs_muestra->resultado_mensaje;
                                   $aux_ml_existe = explode("Total", $ensayo_vs_muestra->resultado_mensaje);
                                   $aux_ml_existe = trim($aux_ml_existe[1]);
                                   if(!empty($aux_ml_existe)){
                                        $ensayo_vs_muestra->resultado_mensaje =   $aux_ml_existe;
                                   }
                                   $evaluar = evalua_alerta($muestra->ensayos[$key]->med_valor_min, $muestra->ensayos[$key]->med_valor_max , $ensayo_vs_muestra->resultado_mensaje, $muestra->id_tipo_analisis, $aux_id_ensayo_vs_muestra,2);
                                    if(preg_match("/-MAS-/", $evaluar)){//2 para que no genere correos
                                       $aux_input2          = str_replace('valid', 'invalid', $aux_input2);
                                       $aux_cambiar_campos  = str_replace('valid', 'invalid', $aux_cambiar_campos);
                                    }
                                }
                            }
                            $mohos = strripos($muestra->parametros[$key]->parametro_descripcion, 'mohos');
                            $levaduras = strripos($muestra->parametros[$key]->parametro_descripcion, 'levadura');
                        ?>
                        <?php if ( ($mohos !== false && $levaduras >1) && (!preg_match("/--r/", $aux_value)) && ($muestra->certificado_nro>187069) ): ?>
                            <?php
                                $aux_ml="Captura de mohos y levaduras";
                                           // modificamos tip, pendiene de valiar el ultimo campo
                                $aux_mensaje_tip = 'onclick="js_muestra_tip('.$muestra->id_muestreo.', `'.$aux_codigo_amc.'`, `'.$muestra->parametros[$key]->parametro.'`, `'.$muestra->mue_identificacion.'`, `'.$muestra->pro_nombre.'`, `Ingrese el valor de Mohos y levaduras separados por punto y coma (;)`)"';
                                $aux_input2 = '<input type="text" name="frm_resultado2'.$aux_id_ensayo_vs_muestra.'" id="frm_resultado2'.$aux_id_ensayo_vs_muestra.'" '.$aux_cambiar_campos2.' value="'.$aux_value2.'">
                                    <label for="frm_resultado2'.$aux_id_ensayo_vs_muestra.'">Levadura</label>
                                ';
                                // incluimos una bandera para identificar en la fucnion
                                $aux_cambiar_campos = str_replace( ")", ", 'mohos')", $aux_cambiar_campos);
                                $aux_input2 = str_replace( ")", ", 'levaduras')", $aux_input2);
                                if(preg_match('/invalid/', $aux_cambiar_campos)){
                                    $aux_input2  = str_replace('valid', 'invalid', $aux_input2);
                                }
                            ?>
                            <td>
                                <div class="input-field col s12">
                                    <input type="text" name="frm_resultado<?= $aux_id_ensayo_vs_muestra ?>_mohos" id="frm_resultado<?= $aux_id_ensayo_vs_muestra ?>" <?= $aux_cambiar_campos ?> <?= $aux_mensaje_tip ?> value="<?= $aux_value ?>">
                                    <label for="frm_resultado<?= $aux_id_ensayo_vs_muestra ?>">Moho:</label>
                                </div>
                                <div class="input-field col s12" id="campo_respuesta2_'<?= $aux_id_ensayo_vs_muestra ?>">
                                        <?= $aux_input2 ?>
                                </div>
                                    <?= $aux_div_rta ?>
                                    <div id="campo_mensajes_<?= $aux_id_ensayo_vs_muestra ?>"><?= $aux_mensaje_respuesta ?></div>
                            </td>
                        <?php else: ?>
                            <td>
                                <!-- <?= $ensayo_vs_muestra->resultado_mensaje ?> -->
                                <div class="input-field col l12">
                                    <input type="text" name="frm_resultado<?= $aux_id_ensayo_vs_muestra ?>" id="frm_resultado<?= $aux_id_ensayo_vs_muestra ?>" <?= $aux_cambiar_campos ?> <?= $aux_mensaje_tip ?> value="<?= $aux_value ?>">
                                </div>
                                <div class="input-field col l12" id="campo_respuesta2_<?= $aux_id_ensayo_vs_muestra ?>">
                                    <?= $aux_input2 ?>
                                </div>
                                <?= $aux_div_rta ?>
                                <div id="campo_mensajes_<?= $aux_id_ensayo_vs_muestra?>">
                                    <?= $aux_mensaje_respuesta ?>
                                </div>
                            </td>
                        <?php endif ?>
            <?php endforeach ?>
        </tr>
    </tbody>
                        </table>
                        </form>
                    </div>
                </div>
            </li>
        <?php endforeach ?>
    </ul>
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
<script src="<?= base_url() ?>/assets/js/funcionarios/funciones.js"></script>
<script src="<?= base_url(['assets', 'js', 'funcionarios', 'resultados.js']) ?>"></script>

