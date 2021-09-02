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
                                        Ingreso de muestras
                                    </h2>
                                    <hr>
    <ul class="collapsible popout">
        <li class="active">
            <div class="collapsible-header">Datos de la empresa</div>
            <div class="collapsible-body">
                <div class="row">
                    <div class="col s12">
                        <form action="<?= base_url(['funcionario','remiciones','empresa']) ?>" method="POST" id="remicion-form" autocomplete="off">
                            <div class="row empresa_row">
                                <div class="col l12 s12">
                                    <div class="input-field col l4 s12 empresa">
                                        <input id="empresa" name="empresa" type="text" class="validate autocomplete empresa">
                                        <label for="empresa">Empresa</label>
                                        <small class=" red-text text-darken-4" id="empresa"></small>
                                    </div>
                                    <div class="input-field col l2 s12">
                                        <input id="sucursal" name="sucursal" type="text" class="validate">
                                        <label for="sucursal">Sucursal</label>
                                        <small class=" red-text text-darken-4" id="sucursal"></small>
                                    </div>

                                    <div class="input-field col l6 s12 nit">
                                        <input id="nit" name="nit" type="text" class="validate">
                                        <label for="nit">Nit/Cédula</label>
                                        <small class=" red-text text-darken-4" id="nit"></small>
                                    </div>

                                    <div class="input-field col l5 s12">
                                        <input id="name_contact" name="name_contact" type="text" class="validate">
                                        <label for="name_contact">Nombre del contacto</label>
                                        <small class=" red-text text-darken-4" id="name_contact"></small>
                                    </div>
                                    <div class="input-field col l2 s12">
                                        <input id="cargo" name="cargo" type="text" class="validate">
                                        <label for="cargo">Cargo</label>
                                        <small class=" red-text text-darken-4" id="cargo"></small>
                                    </div>
                                    <div class="input-field col l5 s12">
                                        <input id="phone" name="phone" type="text" class="validate">
                                        <label for="phone">Telefono</label>
                                        <small class=" red-text text-darken-4" id="phone"></small>
                                    </div>
                                    <div class="input-field col l6 s12">
                                        <input id="fax" name="fax" type="text" class="validate">
                                        <label for="fax">Numero de fax</label>
                                        <small class=" red-text text-darken-4" id="fax"></small>
                                    </div>
                                    <div class="input-field col l6 s12">
                                        <input id="email" name="email" type="text" class="validate">
                                        <label for="email">Email</label>
                                        <small class=" red-text text-darken-4" id="email"></small>
                                    </div>

                                    
                                    <div class="input-field col l6 s12">
                                        <input id="direction" name="direction" type="text" class="validate">
                                        <label for="direction">Dirección</label>
                                        <small class=" red-text text-darken-4" id="direction"></small>
                                    </div>
                                    <div class="input-field col l4 s12">
                                        <input name="date" id="date" type="date" class="validate" value="<?= date('Y-m-d') ?>" disabled >
                                        <label for="date">Fecha de registro</label>
                                    </div>
                                    <div class="input-field col l2 s12 hora">
                                        <input name="hora" id="hora" type="text" class="validate" value="<?=date('H:i:s')?>" disabled >
                                        <label for="hora">Hora</label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="input-field col s12 centrar_button">
                                    <button class="btn gradient-45deg-purple-deep-orange border-round" id="btn-empresa">
                                        Guardar empresa
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </li>
    </ul>
    <ul class="collapsible popout">
        <li class="active">
            <div class="collapsible-header">Muestra</div>
            <div class="collapsible-body">
                <div class="row">
                    <div class="col s12">
                        <form action="<?= base_url(['funcionario','remiciones','muestra']) ?>" method="POST" id="muestra-form" autocomplete="off">
                            <div class="row">
                                <div class="input-field col l4 s12">
                                    <input id="id_muestra" name="id_muestra" type="text" class="validate">
                                    <label for="id_muestra">Identificación de la muestra</label>
                                </div>
                                <div class="input-field col l8 s12 producto">
                                    <input id="producto" name="producto" type="text" class="validate autocomplete producto">
                                    <label for="producto">Producto</label>
                                </div>
                                <div class="input-field col l4 s12">
                                    <input id="lote" name="lote" type="text" class="validate">
                                    <label for="lote">Número de lote</label>
                                </div>
                                <div class="input-field col l4 s12">
                                    <input id="fecha_produccion" name="fecha_produccion" type="date" class="validate">
                                    <label for="fecha_produccion">Fecha de producción</label>
                                </div>
                                <div class="input-field col l4 s12">
                                    <input id="fecha_vencimiento" name="fecha_vencimiento" type="date" class="validate">
                                    <label for="fecha_vencimiento">Fecha de vencimiento</label>
                                </div>
                                <div class="input-field col l4 s12">
                                    <input id="temperatura" name="Temperatura" type="text" class="validate">
                                    <label for="temperatura">Temperatura</label>
                                </div>
                                <div class="input-field col l4 s12">
                                    <input id="momento" name="momento" type="text" class="validate">
                                    <label for="momento">Momento del muestreo</label>
                                </div>
                                <div class="input-field col l4 s12">
                                    <input id="temperatura_recepcion" name="temperatura_recepcion" type="text" class="validate">
                                    <label for="temperatura_recepcion">Temperatura de recepción</label>
                                </div>
                                <div class="input-field col l4 s12">
                                    <input id="condiciones" name="condiciones" type="text" class="validate">
                                    <label for="condiciones">Condiciones de recibido</label>
                                </div>
                                <div class="input-field col l4 s12">
                                    <input id="cantidad" name="cantidad" type="text" class="validate" value="1">
                                    <label for="cantidad">Cantidad</label>
                                </div>
                                <div class="input-field col l4 s12">
                                    <input id="procedencia" name="procedencia" type="text" class="validate">
                                    <label for="procedencia">Procedencia</label>
                                </div>
                                <div class="input-field col l4 s12">
                                    <input id="tipo" name="tipo" type="text" class="validate">
                                    <label for="tipo">Tipo de muestra</label>
                                </div>
                                <div class="input-field col l4 s12">
                                    <input id="area" name="area" type="text" class="validate">
                                    <label for="area">Area / Función</label>
                                </div>
                                <div class="input-field col l4 s12">
                                    <input id="tipo_muestreo" name="tipo_muestreo" type="text" class="validate">
                                    <label for="tipo_muestreo">Tipo de muestreo</label>
                                </div>
                                <div class="input-field col l4 s12">
                                    <input id="adicional" name="adicional" type="text" class="validate">
                                    <label for="adicional">Adicional para muestra</label>
                                </div>
                                <div class="input-field col l4 s12">
                                    <input id="medida" type="text" name="medidad" class="validate">
                                    <label for="medida">Medida para muestra</label>
                                </div>
                                <div class="input-field col l4 s12">
                                    <select name="tipo_analisis">
                                        <option value="">Seleccione analisis</option>
                                        <?php foreach ($analisis as $key => $value): ?>
                                            <option value="<?= $value->id_muestra_tipo_analsis ?>"><?= $value->mue_nombre ?> / <?= $value->mue_sigla ?></option>
                                        <?php endforeach ?>
                                    </select>
                                    <label>Análisis solicitado</label>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </li>
    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?= view('layouts/footer') ?>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="<?= base_url() ?>/assets/js/funcionarios/remicion.js"></script>