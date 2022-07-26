<?php
global $CONTROLLER;
$oldLocale = setlocale(LC_TIME, 'it_IT');
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Saverio Leoni">
    <link rel='shortcut icon' href="<?= SERV_URL ?>dist/images/favicon.png" type="image/png" />

    <title><?= $var_in_view['pageTitle'] ?> &bull; Mediamente</title>

    <!-- Bootstrap core CSS -->
    <link href="<?= SERV_URL ?>dist/css/bootstrap.min.css?v=<?= VERSION ?>" rel="stylesheet">

    <!-- FlatIcon -->
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

    <!-- Custom Fonts  -->
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Nunito+Sans:wght@400;600;700;800&display=swap');
    </style>

    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>

    <!-- Custom styles for this template -->
    <link href="<?= SERV_URL ?>dist/css/dashboard.css?v=<?= VERSION ?>" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?= SERV_URL ?>src/css/style.css" />


    <!-- JQuery -->
    <script src="<?= SERV_URL ?>dist/js/jquery-3.3.1.min.js"></script>
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/v/bs/dt-1.10.16/fh-3.1.3/sl-1.2.5/datatables.min.css" />
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs/dt-1.10.16/fh-3.1.3/sl-1.2.5/datatables.min.js">
    </script>

    <!-- Custom Javascript Functions for Ajax Responses for dynamic UI  -->
    <script defer src="<?= SERV_URL ?>src/js/functions.js"></script>
    <script defer src="<?= SERV_URL ?>src/js/elements.js"></script>
    <script defer src="<?= SERV_URL ?>src/js/macchine-index.js"></script>


</head>

<body>
    <script>
    function decodeEntities(encodedString) {
        var textArea = document.createElement('textarea');
        textArea.innerHTML = encodedString;
        return textArea.value;
    }
    </script>
    <!-- Navbar -->
    <?php
    require_once ROOT_PATH . 'views/inc/navbar.php';
    echo renderNavbar($CONTROLLER);
    ?>
    <!-- Sidebar  -->
    <?php
    require_once ROOT_PATH . 'views/inc/sidebar.php';
    echo renderSidebar($CONTROLLER);
    ?>
    <!-- Page Content -->
    <div class="page">
        <?php
        printFlash();
        ?>
        <?php include_once $view;
        if (function_exists('renderView')) {
            call_user_func('renderView', $var_in_view);
        }
        ?>
    </div>

    <?php /*if(isset($var_in_view['printDebugQuery']))
			echo '<pre>'.print_r($var_in_view['printDebugQuery']).'</pre>'
			*/
    ?>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?= SERV_URL ?>dist/js/bootstrap.min.js"></script>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="<?= SERV_URL ?>dist/bootstrap-select/css/bootstrap-select.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="<?= SERV_URL ?>dist/bootstrap-select/js/bootstrap-select.min.js"></script>

    <div id='ajax_loader' class="loading">
        <div class="loading-inner">
            <img src="<?= SERV_URL ?>dist/images/ajax-loader.gif" />
        </div>
    </div>
</body>

</html>