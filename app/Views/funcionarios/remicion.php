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
                        <form>
                            <div class="row">
                                <div class="col l6 s12">
                                    <div class="input-field col l6 s12">
                                        <input id="empresa" type="text" class="validate">
                                        <label for="empresa">Empresa</label>
                                    </div>
                                    <div class="input-field col l6 s12">
                                        <input id="sucursal" type="text" class="validate">
                                        <label for="sucursal">Sucursal</label>
                                    </div>

                                    <div class="input-field col l3 s12">
                                        <input id="cargo" type="text" class="validate">
                                        <label for="cargo">Cargo</label>
                                    </div>
                                    <div class="input-field col l9 s12">
                                        <input id="name_contact" type="text" class="validate">
                                        <label for="name_contact">Nombre del contacto</label>
                                    </div>
                                    <div class="input-field col l12 s12">
                                        <input id="fax" type="text" class="validate">
                                        <label for="fax">Numero de fax</label>
                                    </div>
                                    <div class="input-field col l12 s12">
                                        <input id="email" type="text" class="validate">
                                        <label for="email">Email</label>
                                    </div>
                                </div>
                                <div class="col l6 s12">
                                    <div class="input-field col l12 s12">
                                        <input id="nit" type="text" class="validate">
                                        <label for="nit">Nit/Cédula</label>
                                    </div>
                                    <div class="input-field col l12 s12">
                                        <input id="phone" type="text" class="validate">
                                        <label for="phone">Telefono</label>
                                    </div>
                                    <div class="input-field col l12 s12">
                                        <input id="direction" type="text" class="validate">
                                        <label for="direction">Dirección</label>
                                    </div>
                                    <div class="input-field col l12 s12">
                                        <input id="date" type="date" class="validate" value="<?= date('d/m/Y H:i:s') ?>">
                                        <label for="date">Fecha de muestreo</label>
                                    </div>
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
