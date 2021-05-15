<?= view('layouts/header') ?>
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
<?= view('layouts/navbar_vertical') ?>
<?= view('layouts/navbar_horizontal') ?>
    <!-- BEGIN: Page Main-->
<?php if ( !empty(configInfo()['intro']) && isset(configInfo()['intro'])): ?>
    <div id="main">
        <div class="row">
            <div class="col s12">
                <div class="container">
                    <div class="section">
                        <div class="card">
                            <div class="card-content">
                                <?= configInfo()['intro'] ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div id="main">
        <div class="row">
            <div class="col s12">
                <div class="container">
                    <div class="section">
                        <div id="sales-chart">
                            <div class="row">
                                <div class="col s12">
                                    <?php if (session('error')): ?>
                                        <div class="card-alert card red">
                                            <div class="card-content white-text">
                                                <p><?= session('error') ?></p>
                                            </div>
                                            <button type="button"class="close white-text" data-dismiss="alert"
                                                    aria-label="Close">
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (session('success')): ?>
                                        <div class="card-alert card green">
                                            <div class="card-content white-text">
                                                <p><?= session('success') ?></p>
                                            </div>
                                            <button type="button"class="close white-text" data-dismiss="alert"
                                                    aria-label="Close">
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="col s12">
                                    <div id="revenue-chart" class="card animate fadeUp">
                                        <div class="card-content">
                                            <h4 class="card-title">
                                                Certificados 
                                            </h4>
        <div class="row">
            <div class="col s12">
                <form autocomplete="off" id="form_filtrar" action="<?= route_to('filtrar_certificado')?>"method="POST">
                    <!-- <div class="input-field col s12 l3 m6 x13">
                        <select name="limite">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="">Todos</option>
                        </select>
                        <label>Total resultados</label>
                    </div>
                    <div class="input-field col s12 l3 m6 x13 paginas">
                        <select name="pagina" id="pagina" data-total="<?= $count ?>">
                            <?php for($i = 0; $i < $count; $i++): ?>
                                <option value="<?= $i ?>"><?= ($i+1) ?></option>
                            <?php endfor ?>
                        </select>
                        <label>Pagina</label>
                    </div> -->
                    <div class="input-field col s12 l3 m12 x13">
                        <select name="parametros">
                            <option value="0">Sin filtrar</option>
                            <?php foreach ($resultado_parametros as $value):?>
                                <option value="<?=$value->id_parametro?>"><?= $value->par_nombre ?></option>
                            <?php endforeach ?>
                        </select>
                        <label>Parametros</label>
                    </div>
                    <div class="input-field col s12 l3 m12 x13">
                        <select name="tipo_analisis">
                            <option value="0">Sin filtrar</option>
                            <?php foreach ($resultado_muestra as $value):?>
                                <option value="<?=$value->id_muestra_tipo_analsis?>"><?= $value->mue_nombre ?></option>
                            <?php endforeach ?>
                        </select>
                        <label>Tipo de análisis</label>
                    </div>
                    <div class="input-field col s12 l6 m12 x13">
                        <select name="producto">
                            <option value="0">Sin filtrar</option>
                            <?php foreach ($resultado_productos as $value):?>
                                <option value="<?=$value->id_producto?>"><?= $value->producto ?></option>
                            <?php endforeach ?>
                        </select>
                        <label>Productos</label>
                    </div>
                    <div class="input-field col s12 l6 m12 x1">
                        <select name="concepto">
                            <option value="-1">Sin filtrar</option>
                            <?php foreach ($resultado_concepto as $key => $value):?>
                                <option value="<?=$value->id_mensaje?>"><?= $value->mensaje_titulo ? $value->mensaje_titulo:'Concepto vacio' ?></option>
                            <?php endforeach ?>
                        </select>
                        <label>Concepto</label>
                    </div>
                    <div class="input-field col s12 l3 m12 x13">
                        <input name="date_start" autocomplete="off" type="date">
                        <label>Fecha de inicio</label>
                    </div>
                    <div class="input-field col s12 l3 m12 x13">
                        <input name="date_finish" autocomplete="off" type="date">
                        <label>Fecha final</label>
                    </div>
                    <a id="filtrar" class="waves-effect waves-light btn">Filtrar</a>
                    <button type="reset" class="btn red accent-3 reset_btn">Reiniciar</button>
                </form>
            </div>
            <div class="col s12 mostrar_text">
                <p id="r_total">Mostrando: <?= $total_2 ?> de <?= $total ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col s12 l12 m12 x13 tabla_certificados">
                <?php session() ?>
                <form action="<?= route_to('certificado_download') ?>" method="POST">
                    <div id="tabla"></div>
                    <?= $certificados ?>
                    <button class="waves-effect waves-light btn" type="submit">Descargar</button>
                </form>
            </div>
            <div class="col s12">
                <form action="<?= route_to('filtrar_paginar') ?>" method="POST" id="form_pagina">
                    <div class="input-field col s12 l6 m6 x13">
                        <div class="botones_paginador">
                            <a data-pagina="start" class="enviar start deep-purple darken-1" data-page="0" style="display: none;"><i class="fas fa-angle-double-left"></i></a>
                            <a data-pagina="back" class="enviar back deep-purple darken-1" data-page="" style="display: none;"><i class="fas fa-angle-left"></i></a>
                            <p class="pagina_text"> Pagina 1 </p>
                            <a data-pagina="next" class="enviar next deep-purple darken-1" data-page="1"><i class="fas fa-angle-right"></i></a>
                            <a data-pagina="finish" class="enviar finish deep-purple darken-1" data-page="<?= ($count-1) ?>"><i class="fas fa-angle-double-right"></i></a>
                        </div>
                    </div>
                    <div class="input-field col s12 l3 m6 x13 paginador_select">
                        <select name="limite">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="">Todos</option>
                        </select>
                        <label>Número de resultados</label>
                    </div>
                </form>
            </div>
        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?= view('layouts/footer') ?>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    $(document).ready(function () {
        $('#filtrar').click(function (e) {
            e.preventDefault();
            var form    = $('#form_filtrar');
            var form_filtro = $('#form_filtrar').serialize();
            var form_pagina = $('#form_pagina').serialize();
            var data        = form_filtro+'&'+form_pagina+"&pagina=0";
            var url     = form.attr('action');
            var total   = $('#pagina').data('total');
            Swal.fire({
              position: 'top-center',
              html:'<div class="card-content redo"><div class="preloader-wrapper big active"><div class="spinner-layer spinner-blue-only"><div class="circle-clipper left"><div class="circle"></div></div><div class="gap-patch"><div class="circle"></div></div><div class="circle-clipper right"><div class="circle"></div></div></div></div></div><div class="card-action"><p class="load">Cargando...</p></div>',
              showConfirmButton: false,
              allowOutsideClick: false,
            })
            $.post(url, data, function(resultado) {
                var resultado = JSON.parse(resultado);
                $('.responsive-table').remove();
                $('#tabla').append(resultado['certificados']);
                $('#r_total').text('Mostrando: '+resultado['total_2']+' de '+resultado['total']);
                $('.pagina_text').html('Pagina '+1);
                for (var i = 0; i < resultado['count']; i++) {
                    $('.enviar.finish').attr('data-page', i );
                }
                $('.enviar.next').attr('data-page', 1);
                if ( $('.enviar.finish').attr('data-page') == resultado['pagina'])
                    $('.enviar.next').fadeOut();
                else
                    $('.enviar.next').fadeIn();

                if( (parseInt(resultado['pagina'])+1) >= $('.enviar.finish').attr('data-page')){
                    $('.enviar.finish').fadeOut();
                }else
                    $('.enviar.finish').fadeIn();

                $('.enviar.back').fadeOut();
                $('.enviar.start').fadeOut();
                console.log(resultado);
                Swal.close();
            }).fail(function() {
                Swal.close();
                $('.responsive-table').fadeIn();
            })
        });
        $('.enviar').click(function (e) {
            e.preventDefault();
            var form_filtro = $('#form_filtrar').serialize();
            var form_pagina = $('#form_pagina').serialize();
            var url         = $('#form_pagina').attr('action');
            var data        = form_filtro+'&'+form_pagina+"&pagina="+$(this).attr('data-page');
            console.log(data);
            Swal.fire({
              html:'<div class="card-content redo"><div class="preloader-wrapper big active"><div class="spinner-layer spinner-blue-only"><div class="circle-clipper left"><div class="circle"></div></div><div class="gap-patch"><div class="circle"></div></div><div class="circle-clipper right"><div class="circle"></div></div></div></div></div><div class="card-action"><p class="load">Cargando...</p></div>',
              showConfirmButton: false,
              allowOutsideClick: false,
            })
            // console.log(data);
            // console.log($(this).data('page'));
            $.post(url, data, function(resultado){
                var resultado = JSON.parse(resultado);
                $('.responsive-table').remove();
                $('#tabla').append(resultado['certificados']);
                $('#r_total').text('Mostrando: '+resultado['total_2']+' de '+resultado['total']);
                $('.pagina_text').html('Pagina '+(parseInt(resultado['pagina'])+parseInt(1)));
                $('.enviar.next').attr('data-page', (parseInt(resultado['pagina'])+parseInt(1)));
                $('.enviar.back').attr('data-page', (parseInt(resultado['pagina'])-parseInt(1)));
                if( resultado['pagina'] < 2)
                    $('.enviar.start').fadeOut();
                else
                    $('.enviar.start').fadeIn();
                if( resultado['pagina'] < 1)
                    $('.enviar.back').fadeOut();
                else
                    $('.enviar.back').fadeIn();

                if ( $('.enviar.finish').attr('data-page') <= resultado['pagina'])
                    $('.enviar.next').fadeOut();
                else
                    $('.enviar.next').fadeIn();

                if( (parseInt(resultado['pagina'])+1) >= $('.enviar.finish').attr('data-page')){
                    $('.enviar.finish').fadeOut();
                }else
                    $('.enviar.finish').fadeIn();
                Swal.close();
            });
        });
        var id_selct = $('.paginador_select .select-wrapper .select-dropdown.dropdown-trigger').data('target')
        $('.paginador_select .dropdown-content.select-dropdown li').click(function(e) {
            var form_filtro = $('#form_filtrar').serialize();
            var form_pagina = $('#form_pagina').serialize();
            var data        = form_filtro+'&'+form_pagina+"&pagina=0";
            var url         = $('#form_pagina').attr('action');
            Swal.fire({
                html:'<div class="card-content redo"><div class="preloader-wrapper big active"><div class="spinner-layer spinner-blue-only"><div class="circle-clipper left"><div class="circle"></div></div><div class="gap-patch"><div class="circle"></div></div><div class="circle-clipper right"><div class="circle"></div></div></div></div></div><div class="card-action"><p class="load">Cargando...</p></div>',
                showConfirmButton: false,
                allowOutsideClick: false,
            })
            // console.log(data);
            // console.log($(this).data('page'));
            $.post(url, data, function(resultado){
                var resultado = JSON.parse(resultado);
                $('.responsive-table').remove();
                $('#tabla').append(resultado['certificados']);
                $('#r_total').text('Mostrando: '+resultado['total_2']+' de '+resultado['total']);
                $('.pagina_text').html('Pagina '+1);
                for (var i = 0; i < resultado['count']; i++) {
                    $('.enviar.finish').attr('data-page', i );
                }

                if ( $('.enviar.finish').attr('data-page') <= resultado['pagina'])
                    $('.enviar.next').fadeOut();
                else
                    $('.enviar.next').fadeIn();

                if( (parseInt(resultado['pagina'])+1) >= $('.enviar.finish').attr('data-page')){
                    $('.enviar.finish').fadeOut();
                }else
                    $('.enviar.finish').fadeIn();
                $('.enviar.next').attr('data-page', 1);
                $('.enviar.back').fadeOut();
                $('.enviar.start').fadeOut();
                Swal.close();
            });
        });
        $('.reset_btn').click(function () {
           $('#form_pagina')[0].reset();
        });
    });
</script>