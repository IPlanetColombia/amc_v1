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
                        <form action="<?= base_url(['funcionario','remisiones','empresa']) ?>" method="POST" id="frm_form" autocomplete="off">
                            <input type='hidden' name='frm_nombre_empresa2' id='frm_nombre_empresa2' class='required'/>
                            <input type="hidden" name="empresa_nueva" id="empresa_nueva" value="1">
                            <input type="hidden" id="click" value="0">
                            <div class="row empresa_row">
                                <div class="col s12">
                                    <div class="row">
                                        <div class="input-field col l4 s12 empresa">
                                            <input id="frm_nombre_empresa" name="frm_nombre_empresa" type="text" class="autocomplete frm_nombre_empresa">
                                            <label for="frm_nombre_empresa">Empresa</label>
                                            <small class=" red-text text-darken-4" id="frm_nombre_empresa"></small>
                                        </div>
                                        <div class="input-field col l2 s12">
                                            <input id="frm_nombre_empresa_subtitulo" name="frm_nombre_empresa_subtitulo" type="text" class="validate">
                                            <label for="frm_nombre_empresa_subtitulo">Sucursal</label>
                                            <small class=" red-text text-darken-4" id="frm_nombre_empresa_subtitulo"></small>
                                        </div>
                                        <div class="input-field col l6 s12 nit">
                                            <input id="frm_nit" name="frm_nit" type="text" class="validate nit">
                                            <label for="frm_nit">Nit/Cédula</label>
                                            <small class=" red-text text-darken-4" id="frm_nit"></small>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="input-field col l5 s12">
                                            <input id="frm_contacto_nombre" name="frm_contacto_nombre" type="text" class="validate">
                                            <label for="frm_contacto_nombre">Nombre del contacto</label>
                                            <small class=" red-text text-darken-4" id="frm_contacto_nombre"></small>
                                        </div>
                                        <div class="input-field col l2 s12">
                                            <input id="frm_contacto_cargo" name="frm_contacto_cargo" type="text" class="validate">
                                            <label for="frm_contacto_cargo">Cargo</label>
                                            <small class=" red-text text-darken-4" id="frm_contacto_cargo"></small>
                                        </div>
                                        <div class="input-field col l5 s12">
                                            <input id="frm_telefono" name="frm_telefono" type="text" class="validate">
                                            <label for="frm_telefono">Telefono</label>
                                            <small class=" red-text text-darken-4" id="frm_telefono"></small>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col l6 s12">
                                            <input id="frm_fax" name="frm_fax" type="text" class="validate">
                                            <label for="frm_fax">Numero de fax</label>
                                            <small class=" red-text text-darken-4" id="frm_fax"></small>
                                        </div>
                                        <div class="input-field col l6 s12">
                                            <input id="frm_correo" name="frm_correo" type="text" class="validate">
                                            <label for="frm_correo">Email</label>
                                            <small class=" red-text text-darken-4" id="frm_correo"></small>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="input-field col l6 s12">
                                            <input id="frm_direccion" name="frm_direccion" type="text" class="validate">
                                            <label for="frm_direccion">Dirección</label>
                                            <small class=" red-text text-darken-4" id="frm_direccion"></small>
                                        </div>
                                        <div class="input-field col l4 s12 fecha_muestra">
                                            <input name="frm_fecha_muestra" id="frm_fecha_muestra" type="text" class="" value="<?= date('Y-m-d')?>" readonly>
                                            <label for="frm_fecha_muestra">Fecha de muestreo</label>
                                        </div>
                                        <div class="input-field col l2 s12 hora">
                                            <input name="frm_hora_muestra" id="frm_hora_muestra" type="text" class="" value="<?= date('H:i:s')?>" readonly>
                                            <label for="frm_hora_muestra">Hora</label>
                                        </div>
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
                        <form action="<?= base_url(['funcionario','remisiones','muestra']) ?>" method="POST" id="frm_form_muestra" autocomplete="off">
                            <div class="row">
                                <div class="input-field col l4 s12">
                                    <input id="frm_identificacion" name="frm_identificacion" type="text" class="validate">
                                    <label for="frm_identificacion">Identificación de la muestra</label>
                                    <small class="red-text text-darken-4" id="frm_identificacion"></small>
                                </div>
                                <div class="input-field col l4 s12">
                                    <input id="frm_lote" name="frm_lote" type="text" class="validate">
                                    <label for="frm_lote">Número de lote</label>
                                </div>
                                <div class="input-field col l4 s12">
                                    <input id="frm_fecha_produccion" name="frm_fecha_produccion" type="date" class="validate">
                                    <label for="frm_fecha_produccion">Fecha de producción</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col l4 s12">
                                    <input id="frm_fecha_vencimiento" name="frm_fecha_vencimiento" type="date" class="validate">
                                    <label for="frm_fecha_vencimiento">Fecha de vencimiento</label>
                                </div>
                                <div class="input-field col l4 s12">
                                    <input id="frm_tmp_muestreo" name="frm_tmp_muestreo" type="text" class="validate">
                                    <label for="frm_tmp_muestreo">Temperatura</label>
                                </div>
                                <div class="input-field col l4 s12">
                                    <input id="frm_momento_muestreo" name="frm_momento_muestreo" type="text" class="validate">
                                    <label for="frm_momento_muestreo">Momento del muestreo</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col l4 s12">
                                    <input id="frm_tmp_recepcion" name="frm_tmp_recepcion" type="text" class="validate">
                                    <label for="frm_tmp_recepcion">Temperatura de recepción</label>
                                </div>
                                <div class="input-field col l4 s12">
                                    <input id="frm_condiciones_recibido" name="frm_condiciones_recibido" type="text" class="validate">
                                    <label for="frm_condiciones_recibido">Condiciones de recibido</label>
                                </div>
                                <div class="input-field col l4 s12 cantidad">
                                    <input id="frm_cantidad" name="frm_cantidad" type="text" class="validate" value="1">
                                    <label for="frm_cantidad">Cantidad</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col l4 s12">
                                    <input id="frm_procedencia" name="frm_procedencia" type="text" class="validate">
                                    <label for="frm_procedencia">Procedencia</label>
                                </div>
                                <div class="input-field col l4 s12">
                                    <input id="frm_parametro" name="frm_parametro" type="text" class="validate">
                                    <label for="frm_parametro">Tipo de muestra</label>
                                </div>
                                <div class="input-field col l4 s12">
                                    <input id="frm_area" name="frm_area" type="text" class="validate">
                                    <label for="frm_area">Area / Función</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col l4 s12">
                                    <input id="frm_tipo_muestreo" name="frm_tipo_muestreo" type="text" class="validate">
                                    <label for="frm_tipo_muestreo">Tipo de muestreo</label>
                                </div>
                                <div class="input-field col l4 s12">
                                    <input id="frm_adicional" name="frm_adicional" type="text" class="validate">
                                    <label for="frm_adicional">Adicional para muestra</label>
                                </div>
                                <div class="input-field col l4 s12">
                                    <input id="frm_medida" type="text" name="frm_medida" class="validate">
                                    <label for="frm_medida">Medida para muestra</label>
                                </div>
                            </div>
                            <div class="row finish">
                                <div class="input-field col l4 s12 frm_analisis">
                                    <select name="frm_analisis" id="frm_analisis">
                                        <option value="">Seleccione analisis</option>
                                        <?php foreach ($analisis as $key => $value): ?>
                                            <option value="<?= $value->id_muestra_tipo_analsis ?>"><?= $value->mue_nombre ?> / <?= $value->mue_sigla ?></option>
                                        <?php endforeach ?>
                                    </select>
                                    <label>Análisis solicitado</label>
                                    <small class="red-text text-darken-4" id="frm_analisis"></small>
                                </div>
                                <div class="input-field col l8 s12 frm_producto">
                                    <input id="frm_producto" name="frm_producto" type="text" class="validate autocomplete frm_producto" placeholder="Area/Función">
                                    <label for="frm_producto">Norma</label>
                                </div>
                            </div>
                            <hr>
                            <h5 class="card-title" id="tabla_detalles_muestras">Detalle de remición</h5>
                            <div id="campo_detalle_muestras_basic">
                                <table class="striped centered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Certificado</th>
                                            <th>Tipo de An&aacute;lisis</th>
                                            <th>C&oacute;digo AMC</th>
                                            <th>Norma</th>
                                            <th>Identificaci&oacute;n</th>
                                            <th>Cantidad</th>
                                            <th>Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td scope="row">1</td>
                                            <td>@Certificado</td>
                                            <td>@Tipo</td>
                                            <td>@C&oacute;digo AMC</td>
                                            <td>@Norma</td>
                                            <td>@Identificaci&oacute;n</td>
                                            <td>@Cantidad</td>
                                            <td>@Opciones</td>
                                          </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div id="campo_detalle_muestras">
                            </div>
                        </form>
                        <form id="frm_form_pie" method="POST" action="<?= base_url(['funcionario','remisiones','muestra']) ?>" autocomplete="off">
                            <div class="row">
                                <div class="input-field col s12 observacion">
                                    <textarea id="frm_observaciones" name="frm_observaciones" class="materialize-textarea"></textarea>
                                    <label for="frm_observaciones">Observaci&oacute;n</label>
                                </div>
                                <div class="input-field col l6 s12 entrega">
                                    <input id="frm_entrega" type="text" name="frm_entrega" class="validate" placeholder="Nombre de quien entrega la muestra">
                                    <label for="frm_entrega">Entrega muestra</label>
                                    <small class="red-text text-darken-4" id="frm_entrega"></small>
                                </div>
                                <div class="input-field col l6 s12 recibe">
                                    <input id="frm_recibe" type="text" name="frm_recibe" class="validate" placeholder="Nombre de quien recibe la muestra">
                                    <label for="frm_recibe">Recibe</label>
                                    <small class="red-text text-darken-4" id="frm_recibe"></small>
                                </div>
                            </div>
                            <div id="campo_id_remision">
                                <input type="hidden" value="0" name="frm_id_remision" id="frm_id_remision"/>
                            </div>
                            <input type="hidden" value="0" name="frm_estado_remision" id="frm_estado_remision"/>
                            <input type="hidden" value="<?=date('Y-m-d H:i:s');?>" name="frm_fecha_recepcion" id="frm_fecha_recepcion"/>
                            <input type="hidden" value="0000-00-00" name="frm_fecha_analisis" id="frm_fecha_analisis"/>
                            <input type="hidden" value="0000-00-00" name="frm_fecha_informe" id="frm_fecha_informe"/>
                            <input type="hidden" value="0" name="frm_responsable" id="frm_responsable"/>
                            <input type="hidden" name="accion" value="0">
                            <div class="row boton_guardar_remision">
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
<script src="<?= base_url() ?>/assets/js/funcionarios/funciones.js"></script>
<script src="<?= base_url() ?>/assets/js/funcionarios/remision.js"></script>
<?php if (isset($muestreo_verifica[0])): ?>
    <?php $fila_cliente_verifica = procesar_registro_fetch('usuario', 'id', $muestreo_verifica[0]->id_cliente); ?>
    <script type="text/javascript">
        my_toast('<p><i class="fas fa-tasks"></i>&nbsp Remición en proceso. (Cargando datos)</p>','light-blue darken-2',5000);
        setTimeout(function(){
            // M.Toast.dismissAll();
            $('#frm_nombre_empresa').val("<?= $fila_cliente_verifica[0]->name ?>");
            buscar_cliente();
            $('#frm_fecha_muestra').val("<?= date('Y/m/d', strtotime($muestreo_verifica[0]->mue_fecha_muestreo)) ?>");
            $('#frm_hora_muestra').val("<?= date('H:i:s', strtotime($muestreo_verifica[0]->mue_fecha_muestreo)) ?>");
            $('#frm_nombre_empresa_subtitulo').val("<?= $muestreo_verifica[0]->mue_subtitulo ?>");
            $('#frm_observaciones').val("<?= $muestreo_verifica[0]->mue_observaciones ?>");
            $('#frm_entrega').val("<?= $muestreo_verifica[0]->mue_entrega_muestra ?>");
            $('#frm_recibe').val("<?= $muestreo_verifica[0]->mue_recibe_muestra ?>");
            $('#frm_id_remision').val("<?= $muestreo_verifica[0]->id_muestreo ?>");
            var tabla = <?= json_encode(imprime_detalle_muestras($muestreo_verifica[0]->id_muestreo),JSON_FORCE_OBJECT)?>;
            $('#campo_detalle_muestras_basic').hide();
            $('#tabla_detalles_muestras').after(tabla.tabla);
            $('.row.boton_guardar_remision').append(tabla.boton);
        }, 1000);
    </script>
<?php endif ?>