<?php
header("HTTP/1.0 500 Internal Server Error");
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

    <title>500 Internal Server Error</title>

    <!-- Bootstrap core CSS -->
    <link href="<?=SERV_URL ?>dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?=SERV_URL ?>dist/css/signin.css" rel="stylesheet">
    
    <script src="<?=SERV_URL ?>dist/js/jquery-1.11.2.min.js"></script>
  </head>

  <body>

    <div class="container">

      <h1>500 Internal Server Error</h1>
      <img src="<?=SERV_URL?>dist/images/grumpy.png" />
      <p>Ops!! Si &egrave; verificato un problema di cui tu non hai colpa.</p>
      <p>Grumpy Cat sta gi&agrave; lavorando per risolverlo.</p>
	  <a href="<?=SERV_URL ?>" class="btn btn-primary">Homepage</a>
    </div> <!-- /container -->
  </body>
</html>
