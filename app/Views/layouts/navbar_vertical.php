<aside class="sidenav-main nav-expanded nav-lock nav-collapsible sidenav-light navbar-full sidenav-active-rounded">
    <div class="brand-sidebar color_secundario_plantilla">
        <h1 class="logo-wrapper"><a class="brand-logo darken-1" href="<?= base_url() ?>/amc-laboratorio/home" style="vertical-align: center;">
                <i class="material-icons"><?= isset(configInfo()['icon_app']) ? configInfo()['icon_app'] : '' ?> </i>
                <span class="logo-text hide-on-med-and-down"
                      style="padding-top: 10px !important; display: block; "><?= isset(configInfo()['name_app']) ? configInfo()['name_app'] : 'IPLANET' ?></span></a><a
                    class="navbar-toggler" href="#"><i class="material-icons">radio_button_checked</i></a></h1>
    </div>


    <ul
        class="sidenav  slide-out sidenav-collapsible leftside-navigation collapsible sidenav-fixed menu-shadow"
        id="slide-out" data-menu="menu-navigation" data-collapsible="menu-accordion">
        <li>
            <div class="user-view" style="">
                <div class="background" style="margin:0px;">
                <img src="<?= isset(configInfo()['background_img_vertical']) ||  !empty(configInfo()['background_img_vertical'])? base_url().'/assets/img/'.configInfo()['background_img_vertical']: base_url().'/assets/img/logo_background.jpeg' ; ?>" style="width: 100%;">
                </div>
                <a href="#" style="margin-right: 0px;"><img class="circle"  style="width: 50px; height:50px;" src="<?= session('user') && session('user')->usr_foto ? base_url().'/assets/img/funcionarios/'.session('user')->usr_foto : base_url().'/assets/img/user.png' ?>"></a>

                <a href="#" style="margin-right: 0px;">
                    <small class="black-text name" style=" font-size: 12px !important; text-shadow: 0 0 1px #000;">
                    <?= session('user')->name ? session('user')->name : session('user')->nombre ?>
                    </small>
                </a>
                <a href="#">
                    <p class="black-text email" style="text-shadow: 0 0 1px #000;">
                        <b><?= session('user')->usertype ? session('user')->usertype : session('user')->cms_rol  ?></b>
                    </p>
                </a>
            </div>
        </li>
        <li class="bold"><a
                    class="waves-effect waves-cyan <?= base_url(uri_string()) == base_url() . '/amc-laboratorio/home' ? 'active' : '' ?> "
                    href="<?= base_url() ?>/amc-laboratorio/home"><i
                        class="material-icons">settings_input_svideo</i><span class="menu-title" data-i18n="Calendar"> Home</span></a>
        </li>
        <?php foreach (menu() as $item): ?>
            <li class="bold <?= isActive(urlOption($item->id)); ?>"><a
                        class="waves-effect waves-cyan  <?= isActive(urlOption($item->id)); ?> <?= countMenu($item->id) ? 'collapsible-header' : ''; ?>"

                        href="<?= countMenu($item->id) ? urlOption() : urlOption($item->id) ?>"><?= $item->icon ?> <span class="menu-title"
                                                                               data-i18n="Calendar"><?= $item->option ?></span></a>

                <?php if (countMenu($item->id)): ?>
                    <div class="collapsible-body">
                        <ul class="collapsible collapsible-sub" data-collapsible="accordion">
                            <?php foreach (submenu($item->id) as $submenu): ?>
                                <li class="<?= isActive(urlOption($submenu->id)); ?>"><a
                                            href="<?= urlOption($submenu->id) ?>"
                                            class="<?= isActive(urlOption($submenu->id)); ?>"><i
                                                class="material-icons">radio_button_unchecked</i><span
                                                data-i18n="Modern"><?= $submenu->option ?></span></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>


    <div class="navigation-background"></div>
    <a class="sidenav-trigger btn-sidenav-toggle btn-floating btn-medium waves-effect waves-light hide-on-large-only"
       href="#" data-target="slide-out"><i class="material-icons">menu</i></a>
</aside>
