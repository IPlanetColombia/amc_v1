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
    <form class="col s12">
        <div class="row">
            <div class="input-field col s12 l3">
                <input id="codigo" type="text" class="validate">
                <label for="codigo">Código Amc</label>
            </div>
            <div class="input-field col s12 l3">
                <input id="year" type="text" class="validate">
                <label for="year">Año</label>
            </div>
            <div class="input-field col s12 l3">
                <input id="day" type="text" class="validate">
                <label for="day">Dia</label>
            </div>
            <div class="input-field col s12 l3">
                <select>
                    <option value="">Todos</option>
                    <?php foreach ($analisis as $key => $value): ?>
                        <option><?= $value->mue_sigla ?></option>
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?= view('layouts/footer') ?>
