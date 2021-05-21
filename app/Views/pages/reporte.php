<?= view('layouts/header') ?>
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
                        <!--yearly & weekly revenue chart start-->
                        <div id="sales-chart">
                            <div class="row">
                                <div class="col s12 m12 l12">
                                    <div id="weekly-earning" class="card animate fadeUp">
                                        <div class="card-content">
                                            <h4 class="card-title">
                                                Reportes 
                                            </h4>
                                            <form autocomplete="off" id="form_reporte" action="<?= base_url(['amc-laboratorio/reporte'])?>"method="POST">
                                                <div class="input-field col s12 l3 m12 x13">
                                                    <select name="parametros">
                                                        <!-- <option value="0">Sin filtrar</option> -->
                                                        <?php foreach ($filtros['resultado_parametros'] as $value):?>
                                                            <option value="<?=$value->id_parametro?>"><?= $value->par_nombre ? $value->par_nombre:'Sin filtrar' ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                    <label>Parametros</label>
                                                </div>
                                                <div class="input-field col s12 l3 m12 x13">
                                                    <select name="tipo_analisis">
                                                        <option value="0">Sin filtrar</option>
                                                        <?php foreach ($filtros['resultado_muestra'] as $value):?>
                                                            <option value="<?=$value->id_muestra_tipo_analsis?>"><?= $value->mue_nombre ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                    <label>Tipo de análisis</label>
                                                </div>
                                                <div class="input-field col s12 l6 m12 x13">
                                                    <select name="producto">
                                                        <option value="0">Sin filtrar</option>
                                                        <?php foreach ($filtros['resultado_productos'] as $value):?>
                                                            <option value="<?=$value->id_producto?>"><?= $value->producto ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                    <label>Productos</label>
                                                </div>
                                                <div class="input-field col s12 l6 m12 x1">
                                                    <select name="concepto">
                                                        <option value="-1">Sin filtrar</option>
                                                        <?php foreach ($filtros['resultado_concepto'] as $key => $value):?>
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
                                            <div id="revenue-chart" class="card animate fadeUp">
                                                <div class="card-content">
                                                    <div class="row">
                                                        <div class="col s12">
                                                            <div class="yearly-revenue-chart">
                                                                <canvas id="miCanvas" class="firstShadow"
                                                                        height="350"></canvas>
                                                                <canvas id="lastYearRevenue" height="350"></canvas>
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

                    </div><!-- START RIGHT SIDEBAR NAV -->
                    
                    <!-- END RIGHT SIDEBAR NAV -->
                </div>
                <div class="content-overlay"></div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?= view('layouts/footer') ?>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    $('#filtrar').click(function (e) {
        var form    = $('#form_reporte');
        var data    = form.serialize();
        var url     = form.attr('action');
        Swal.fire({
            html:'<div class="card-content redo"><div class="preloader-wrapper big active"><div class="spinner-layer spinner-blue-only"><div class="circle-clipper left"><div class="circle"></div></div><div class="gap-patch"><div class="circle"></div></div><div class="circle-clipper right"><div class="circle"></div></div></div></div></div>',
            showConfirmButton: false,
            allowOutsideClick: false,
        })
        $.post(url, data, function (resultado) {
            var resultado   = JSON.parse(resultado);
            var total  = [];
            var mes    = [];
            var y = 0;
            for (var i = 0; i < resultado['data'].length ; i++) {
                total[i]  = parseInt(resultado['data'][i][0]['total']);
                mes[i]     = resultado['data'][i][0]['mes'];
                if(parseInt(resultado['data'][i][0]['total']) >= y ){
                    y = parseInt(resultado['data'][i][0]['total']);
                }
            }
            if(parseInt(y%10) >= 5){
                if(parseInt(y%5) == 0)
                    var limit = '';
                else
                    var limit = 10-parseInt(y%10);
            }else{
                var limit = 5-parseInt(y%10);
            }
            limit = parseInt(y+limit);
            var o = 5;
            var t = 0;
            while(o <= limit){
                o+=5;
                t++;
            }
            $('#miCanvas').remove();
            $('.yearly-revenue-chart').append('<canvas id="miCanvas" class="firstShadow" height="350"></canvas>');
            var thisYearctx = document.getElementById("miCanvas").getContext("2d");

            var thisYearData = {
                labels: mes,
                datasets: [
                    {
                        label: "Certificados",
                        data: total,
                        fill: true,
                        pointRadius: 10,
                        pointBorderWidth: 1,
                        borderColor: "#9C2E9D",
                        borderWidth: 2,
                        pointBorderColor: "#9C2E9D",
                        pointHighlightFill: "#9C2E9D",
                        pointHoverBackgroundColor: "#9C2E9D",
                        pointHoverBorderWidth: 2,
                        order: 1
                    },
                    {
                        label: "Certificados",
                        data: total,
                        fill: false,
                        // pointRadius: 10,
                        // pointBorderWidth: 0.1,
                        borderColor: "#9C2E9D",
                        borderWidth: 0.2,
                        // pointBorderColor: "#9C2E9D",
                        // pointHighlightFill: "#9C2E9D",
                        // pointHoverBackgroundColor: "#9C2E9D",
                        // pointHoverBorderWidth: 0.2,
                        type:'line',
                    }
                ]
            };
           var thisYearOption = {
                responsive: true,
                maintainAspectRatio: true,
                datasetStrokeWidth: 3,
                pointDotStrokeWidth: 4,
                tooltipFillColor: "rgba(0,0,0,0.6)",
                legend: {
                    display: false,
                    position: "bottom"
                },
                hover: {
                    mode: "label"
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            padding: 0,
                            stepSize:t,
                            max: limit,
                            min: 0,
                            fontColor: "#9e9e9e"
                        },
                        gridLines: {
                            display: true,
                            drawBorder: false,
                            lineWidth: 2,
                            zeroLineColor: "#e5e5e5"
                        }
                    }]
                },
                title: {
                    display: true,
                    fontColor: "#000",
                    fullWidth: true,
                    fontSize: 40,
                    text: "Resultados"
                }
            };
            var thisYearChart = new Chart(thisYearctx, {
                type: "bar",
                data: thisYearData,
                options: thisYearOption
            });
            Swal.close();
        })
    })
    $(document).ready(function() {
            // Line chart with color shadow: Revenue for 2018 Chart
           
    });
</script>