<?= view('layouts/header') ?>
<?php if ($title == 'Certificados'): ?>
    <link rel="stylesheet" type="text/css" href="<?= base_url(['assets', 'css', 'funcionario', 'certificados.css']) ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url(['assets', 'css', 'funcionario', 'informe.css']) ?>">
<?php endif ?>
<?= view('layouts/navbar_horizontal') ?>
<?= view('layouts/navbar_vertical') ?>

<!-- BEGIN: Page Main-->
<div id="main">
    <div class="row">
        <div class="col s12">
            <div class="container">
                <div class="section">
                    <div class="card">
                        <div class="card-content card-table">
                            <h4 class="card-title"><?= $title ?></h4>
                            <p><?= $subtitle ?></p>
                                <?=  $output ?>
                        </div>
                        <?php if ($title == 'Certificados'): ?>
                            <div class="card-content card-detalle" style="display:none">
                                <div class="content-info">
                                    <form id="form-certificados" action="<?= base_url(['funcionario', 'certificados']) ?>" method="POST">
                                    </form>
                                </div>
                                <!-- <button class="btn green darken-3" onClick="js_mostrar_detalle(`card-table`, `card-detalle`,``,2,`php_lista_resultados`)">Volver atrás</button> -->
                            </div>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= view('layouts/footer') ?>
<script src="<?= base_url(['assets', 'js', 'funcionarios', 'funciones.js']) ?>"></script>
<script src="<?= base_url(['assets', 'js', 'funcionarios', 'certificacion.js']) ?>"></script>