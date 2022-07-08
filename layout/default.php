<?php
global $CONTROLLER;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Saverio Leoni">

    <link rel='shortcut icon' href="<?= SERV_URL ?>dist/images/favicon.png" type="image/png" />

    <title><?= $var_in_view['pageTitle'] ?> &bull; Mediamente</title>
    <!-- Icons -->
    <link rel="stylesheet" href="https://unpkg.com/flowbite@1.4.7/dist/flowbite.min.css" />
    <script src="https://kit.fontawesome.com/47b9f31c47.js" crossorigin="anonymous"></script>
    <!-- Bootstrap core CSS -->
    <link href="<?= SERV_URL ?>dist/css/bootstrap.min.css?v=<?= VERSION ?>" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="<?= SERV_URL ?>dist/css/dashboard.css?v=<?= VERSION ?>" rel="stylesheet">
    <!-- Tailwind -->
    <link href="/dist/output.css" rel="stylesheet">
    <script src="../path/to/flowbite/dist/flowbite.js"></script>



    <!-- Fonts -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Nunito+Sans:wght@400;600;700;800&display=swap');
    </style>
    <style>
        @font-face {
            font-family: "Flaticon";
            src: url("<?= SERV_URL ?>dist/css/flaticon/flaticon.eot?v=<?= VERSION ?>");
            src: url("<?= SERV_URL ?>dist/css/flaticon/flaticon.eot?v=<?= VERSION ?>#iefix") format("embedded-opentype"),
                url("<?= SERV_URL ?>dist/css/flaticon/flaticon.woff?v=<?= VERSION ?>") format("woff"),
                url("<?= SERV_URL ?>dist/css/flaticon/flaticon.ttf?v=<?= VERSION ?>") format("truetype"),
                url("<?= SERV_URL ?>dist/css/flaticon/flaticon.svg?v=<?= VERSION ?>") format("svg");
            font-weight: normal;
            font-style: normal;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="<?= SERV_URL ?>dist/css/flaticon/flaticon.css?v=<?= VERSION ?>">

    <!-- JQuery -->
    <script src="<?= SERV_URL ?>dist/js/jquery-3.3.1.min.js"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/dt-1.10.16/fh-3.1.3/sl-1.2.5/datatables.min.css" />

    <script type="text/javascript" src="https://cdn.datatables.net/v/bs/dt-1.10.16/fh-3.1.3/sl-1.2.5/datatables.min.js">
    </script>

</head>

<body>
    <script>
        function decodeEntities(encodedString) {
            var textArea = document.createElement('textarea');
            textArea.innerHTML = encodedString;
            return textArea.value;
        }
    </script>
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?= SERV_URL ?>"><img style="height:35px" src="<?= SERV_URL ?>dist/images/logo_rosso.svg?v=0.8" /></a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li <?= ($CONTROLLER == "panoramica" ? 'class="active"' : '') ?>><a class="desk_hide" href="<?= SERV_URL ?><?php
                                                                                                                                if (isset($_SESSION['filtro_cliente']) && $_SESSION['filtro_cliente'] != "")
                                                                                                                                    echo 'panoramica/index/' . $_SESSION['filtro_cliente'];
                                                                                                                                ?>"><span class="flaticon-home168"></span> Panoramica</a></li>

                    <li <?= ($CONTROLLER == "rapportinator" ? 'class="active"' : '') ?>><a class="desk_hide" href="<?= SERV_URL ?>rapportinator"><span class="glyphicon glyphicon-save-file"></span>
                            Rapportinator</a></li>
                    <li <?= ($CONTROLLER == "calendario" ? 'class="active"' : '') ?>><a class="desk_hide" href="<?= SERV_URL ?>calendario"><span class="glyphicon glyphicon-calendar"></span>
                            Calendari</a></li>
                    <li <?= ($CONTROLLER == "progetto" ? 'class="active"' : '') ?>><a class="desk_hide" href="<?= SERV_URL ?>progetto"><span class="glyphicon glyphicon-tasks"></span>
                            Progetti</a></li>
                    <li <?= ($CONTROLLER == "team" ? 'class="active"' : '') ?>><a class="desk_hide" href="<?= SERV_URL ?>team"><span class="glyphicon glyphicon-knight"></span>
                            Team</a></li>
                    <?php if (getMyUsername() == "dchiarello" or getMyUsername() == "mbrianda" or getMyUsername() == "sleoni") { ?>
                        <li <?= ($CONTROLLER == "turni" ? 'class="active"' : '') ?>><a class="desk_hide" href="<?= SERV_URL ?>turni"><span class="glyphicon glyphicon-retweet"></span>
                                Turni</a></li>
                    <?php } ?>
                    <li <?= ($CONTROLLER == "dotazioni" ? 'class="active"' : '') ?>><a class="desk_hide" href="<?= SERV_URL ?>dotazioni"><span class="glyphicon glyphicon-hdd"></span>
                            Dotazioni</a></li>
                    <li <?= ($CONTROLLER == "macchine" ? 'class="active"' : '') ?>><a class="desk_hide" href="<?= SERV_URL ?>macchine"><span class="glyphicon glyphicon-transfer"></span>
                            Macchine</a></li>

                    <li><a href="<?= SERV_URL ?>utenti/logout"><span class="flaticon-powerbuttons"></span> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-3 col-md-1 sidebar">
                <ul class="nav nav-sidebar">
                    <li <?= ($CONTROLLER == "panoramica" ? 'class="active"' : '') ?>><a href="<?= SERV_URL ?><?php
                                                                                                                if (isset($_SESSION['filtro_cliente']) && $_SESSION['filtro_cliente'] != "")
                                                                                                                    echo 'panoramica/index/' . $_SESSION['filtro_cliente'];
                                                                                                                ?>"><i class="flaticon-home168"></i>
                            Panoramica</a></li>
                </ul>
                <ul class="nav nav-sidebar">

                    <li <?= ($CONTROLLER == "rapportinator" ? 'class="active"' : '') ?>><a href="<?= SERV_URL ?>rapportinator"><span class="glyphicon glyphicon-save-file"></span>
                            Rapportinator</a></li>



                </ul>

                <ul class="nav nav-sidebar">
                    <li <?= ($CONTROLLER == "calendario" ? 'class="active"' : '') ?>><a href="<?= SERV_URL ?>calendario"><span class="glyphicon glyphicon-calendar"></span>
                            Calendari</a></li>
                    <li <?= ($CONTROLLER == "progetto" ? 'class="active"' : '') ?>><a href="<?= SERV_URL ?>progetto"><span class="glyphicon glyphicon-tasks"></span>
                            Progetti</a></li>
                    <li <?= ($CONTROLLER == "team" ? 'class="active"' : '') ?>><a href="<?= SERV_URL ?>team"><span class="glyphicon glyphicon-knight"></span>
                            Team</a></li>
                    <?php if (getMyUsername() == "dchiarello" or getMyUsername() == "mbrianda" or getMyUsername() == "sleoni") { ?>
                        <li <?= ($CONTROLLER == "turni" ? 'class="active"' : '') ?>><a href="<?= SERV_URL ?>turni"><span class="glyphicon glyphicon-retweet"></span>
                                Turni</a></li>
                    <?php } ?>
                </ul>
                <ul class="nav nav-sidebar">

                    <li <?= ($CONTROLLER == "dotazioni" ? 'class="active"' : '') ?>><a href="<?= SERV_URL ?>dotazioni"><span class="glyphicon glyphicon-hdd"></span>
                            Dotazioni</a></li>

                    <li <?= ($CONTROLLER == "macchine" ? 'class="active"' : '') ?>><a href="<?= SERV_URL ?>macchine"><span class="glyphicon glyphicon-transfer"></span>
                            Macchine</a></li>
                </ul>
            </div>
            <div class="col-sm-9 col-sm-offset-3 col-md-11 col-md-offset-1 main">
                <?php
                printFlash();
                ?>
                <?php include $view; ?>
            </div>
        </div>
    </div>
    <?php /*if(isset($var_in_view['printDebugQuery']))
			echo '<pre>'.print_r($var_in_view['printDebugQuery']).'</pre>'
			*/
    ?>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?= SERV_URL ?>dist/js/bootstrap.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="<?= SERV_URL ?>dist/bootstrap-select/js/bootstrap-select.min.js"></script>

    <!-- Tailwind and Flowbite script -->
    <script src="https://unpkg.com/flowbite@1.4.7/dist/flowbite.js"></script>


    <div id='ajax_loader' class="loading">
        <div class="loading-inner">
            <img src="<?= SERV_URL ?>dist/images/ajax-loader.gif" />
        </div>
    </div>
</body>

</html>