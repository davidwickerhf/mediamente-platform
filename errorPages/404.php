<?php
header("HTTP/1.0 404 Not Found");
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

    <title>404 Not Found</title>

    <!-- Bootstrap core CSS -->
    <link href="<?=SERV_URL ?>dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?=SERV_URL ?>dist/css/signin.css" rel="stylesheet">
    
    <script src="<?=SERV_URL ?>dist/js/jquery-1.11.2.min.js"></script>
  </head>

  <body>

    <div class="container">

      <h1>404 Not Found</h1>
      <p>La risorsa cercata non &egrave; stata trovata.</p>
	  <a href="<?=SERV_URL ?>" class="btn btn-primary">Homepage</a>
    </div> <!-- /container -->
  </body>
</html>
