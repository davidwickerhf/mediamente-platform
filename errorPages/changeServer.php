<?php
header("HTTP/1.0 503 Service Unavailable");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Server Cambiato</title>

    <!-- Bootstrap core CSS -->
    <link href="<?=SERV_URL ?>dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?=SERV_URL ?>dist/css/signin.css" rel="stylesheet">
    
    <script src="<?=SERV_URL ?>dist/js/jquery-1.11.2.min.js"></script>
  </head>

  <body>

    <div class="container">

      <h1>Cambio server in corso</h1>
      <p>Riprova ad aggiornare la pagina tra qualche minuto. Se il problema persiste chiudi e riapri il browser.</p>
   	  <p><small><?=$_SERVER['SERVER_ADDR']?></small></p>
    </div> <!-- /container -->
  </body>
</html>
