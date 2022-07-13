<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span
                    class="icon-bar"></span> <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?= SERV_URL ?>"><img style="height:35px"
                    src="<?= SERV_URL ?>dist/images/logo_rosso.svg?v=0.8" /></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li <?= ($CONTROLLER == "panoramica" ? 'class="active"' : '') ?>><a class="desk_hide"
                        href="<?= SERV_URL ?><?php
                                                                                                                            if (isset($_SESSION['filtro_cliente']) && $_SESSION['filtro_cliente'] != "")
                                                                                                                                echo 'panoramica/index/' . $_SESSION['filtro_cliente'];
                                                                                                                            ?>"><span class="flaticon-home168"></span> Panoramica</a></li>

                <li <?= ($CONTROLLER == "rapportinator" ? 'class="active"' : '') ?>><a class="desk_hide"
                        href="<?= SERV_URL ?>rapportinator"><span class="glyphicon glyphicon-save-file"></span>
                        Rapportinator</a></li>
                <li <?= ($CONTROLLER == "calendario" ? 'class="active"' : '') ?>><a class="desk_hide"
                        href="<?= SERV_URL ?>calendario"><span class="glyphicon glyphicon-calendar"></span>
                        Calendari</a></li>
                <li <?= ($CONTROLLER == "progetto" ? 'class="active"' : '') ?>><a class="desk_hide"
                        href="<?= SERV_URL ?>progetto"><span class="glyphicon glyphicon-tasks"></span>
                        Progetti</a></li>
                <li <?= ($CONTROLLER == "team" ? 'class="active"' : '') ?>><a class="desk_hide"
                        href="<?= SERV_URL ?>team"><span class="glyphicon glyphicon-knight"></span>
                        Team</a></li>
                <?php if (getMyUsername() == "dchiarello" or getMyUsername() == "mbrianda" or getMyUsername() == "sleoni") { ?>
                <li <?= ($CONTROLLER == "turni" ? 'class="active"' : '') ?>><a class="desk_hide"
                        href="<?= SERV_URL ?>turni"><span class="glyphicon glyphicon-retweet"></span>
                        Turni</a></li>
                <?php } ?>
                <li <?= ($CONTROLLER == "dotazioni" ? 'class="active"' : '') ?>><a class="desk_hide"
                        href="<?= SERV_URL ?>dotazioni"><span class="glyphicon glyphicon-hdd"></span>
                        Dotazioni</a></li>
                <li <?= ($CONTROLLER == "macchine" ? 'class="active"' : '') ?>><a class="desk_hide"
                        href="<?= SERV_URL ?>macchine"><span class="glyphicon glyphicon-transfer"></span>
                        Macchine</a></li>

                <li><a href="<?= SERV_URL ?>utenti/logout"><span class="flaticon-powerbuttons"></span> Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>




<!-- Sidebar   -->
<div class="col-sm-3 col-md-1 sidebar">
    <ul class="nav nav-sidebar">
        <li <?= ($CONTROLLER == "panoramica" ? 'class="active"' : '') ?>><a href="<?= SERV_URL ?><?php
                                                                                                    if (isset($_SESSION['filtro_cliente']) && $_SESSION['filtro_cliente'] != "")
                                                                                                        echo 'panoramica/index/' . $_SESSION['filtro_cliente'];
                                                                                                    ?>"><i
                    class="flaticon-home168"></i>
                Panoramica</a></li>
    </ul>
    <ul class="nav nav-sidebar">

        <li <?= ($CONTROLLER == "rapportinator" ? 'class="active"' : '') ?>><a href="<?= SERV_URL ?>rapportinator"><span
                    class="glyphicon glyphicon-save-file"></span>
                Rapportinator</a></li>



    </ul>

    <ul class="nav nav-sidebar">
        <li <?= ($CONTROLLER == "calendario" ? 'class="active"' : '') ?>><a href="<?= SERV_URL ?>calendario"><span
                    class="glyphicon glyphicon-calendar"></span>
                Calendari</a></li>
        <li <?= ($CONTROLLER == "progetto" ? 'class="active"' : '') ?>><a href="<?= SERV_URL ?>progetto"><span
                    class="glyphicon glyphicon-tasks"></span>
                Progetti</a></li>
        <li <?= ($CONTROLLER == "team" ? 'class="active"' : '') ?>><a href="<?= SERV_URL ?>team"><span
                    class="glyphicon glyphicon-knight"></span>
                Team</a></li>
        <?php if (getMyUsername() == "dchiarello" or getMyUsername() == "mbrianda" or getMyUsername() == "sleoni") { ?>
        <li <?= ($CONTROLLER == "turni" ? 'class="active"' : '') ?>><a href="<?= SERV_URL ?>turni"><span
                    class="glyphicon glyphicon-retweet"></span>
                Turni</a></li>
        <?php } ?>
    </ul>
    <ul class="nav nav-sidebar">

        <li <?= ($CONTROLLER == "dotazioni" ? 'class="active"' : '') ?>><a href="<?= SERV_URL ?>dotazioni"><span
                    class="glyphicon glyphicon-hdd"></span>
                Dotazioni</a></li>

        <li <?= ($CONTROLLER == "macchine" ? 'class="active"' : '') ?>><a href="<?= SERV_URL ?>macchine"><span
                    class="glyphicon glyphicon-transfer"></span>
                Macchine</a></li>
    </ul>
</div>