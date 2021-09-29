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
                                        Registro de Muestras FisicoQuímicas de Aguas <?= $certificado ? $certificado->id_codigo_amc:'' ?>
                                    </h2>
                                    <hr>
<div class="row">
    <form class="col s12" method="POST" action="<?= base_url(['funcionario', 'resultados', 'aguas']) ?>">
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
        <div class="row">
            <div class="col l12 z-depth-2">
                <form id="form_cambia_campos" method="POST" action="<?= base_url(['funcionario', 'resultados', 'aguas', 'cambiar', 'fq']) ?>">
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
                            <?php foreach (parametros_aguas_fq() as $key => $value): ?>
                                <?php $pinta = pinta_parametro_agua($value[0], $certificado->id_muestra_detalle, session('user')->usr_rol, $value[1], $value[2], $value[3], $value[4], $value[5]) ?>
                                <?php if (!empty($pinta)): ?>
                                    <?= $pinta ?>
                                <?php endif ?>
                            <?php endforeach ?>
                            <?php if (!empty($otros)): ?>
                                <tr>
                                    <td>
                                        <p class="center-align">
                                            <b>OTROS ANALISIS</b>
                                        </p>
                                        <?php $llave = 4 ?>
                                        <?php foreach ($otros as $key => $fila): ?>
                                            <?php if ($llave == 4): ?>
                                                <div class="row">
                                                    <?php $llave = 1 ?>
                                            <?php else: ?>
                                                <?php $llave++ ?>
                                            <?php endif ?>
                                            <div class="input-field col s12 l3">
                                                <input type="text" name="frm_otro<?= $key ?>" id="frm_otro<?= $key ?>"
                                                value="<?= $fila->resultado_mensaje ?>"
                                                onblur="js_cambiar_campos('campo_repuesta_otro_<?= $fila->id_ensayo_vs_muestra ?>', this.value, 'frm_otro<?= $key ?>', 'result_3', '<?= $fila->id_ensayo_vs_muestra ?>', <?= $fila->id_parametro ?>)"
                                                <?= disable_frm($fila->resultado_mensaje, session('user')->id) ?>>
                                                <label for="frm_otro<?= $key ?>"><?= $fila->par_nombre.' - '. $fila->id_parametro ?></label>
                                                <span id="campo_repuesta_otro_<?= $fila->id_ensayo_vs_muestra ?>"></span>
                                            </div>
                                            <?php if ($llave == 4): ?>
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
<script src="<?= base_url(['assets', 'js', 'funcionarios', 'resultadosAGFQ.js']) ?>"></script>

