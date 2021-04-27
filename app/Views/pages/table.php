<?= view('layouts/header') ?>
<?= view('layouts/navbar_horizontal') ?>
<?= view('layouts/navbar_vertical') ?>

<!-- BEGIN: Page Main-->
<div id="main">
    <div class="row">
        <div class="col s12">
            <div class="container">
                <div class="section">
                    <div class="card">
                        <div class="card-content">
                            <h4 class="card-title"><?= $title ?></h4>
                            <p><?= $subtitle ?></p>
                                <?=  $output ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= view('layouts/footer') ?>