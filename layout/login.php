<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?=$var_in_view['pageTitle'] ?></title>
    <link rel='shortcut icon' href="<?=SERV_URL?>dist/images/favicon.png" type="image/png" />

    <!-- Bootstrap core CSS -->
    <link href="<?=SERV_URL ?>dist/css/bootstrap.min.css?v=<?=VERSION ?>" rel="stylesheet">
    
    <!-- FlatIcon -->
    <style>
	@font-face {
		font-family: "Flaticon";
		src: url("<?=SERV_URL ?>dist/css/flaticon/flaticon.eot?v=<?=VERSION ?>");
		src: url("<?=SERV_URL ?>dist/css/flaticon/flaticon.eot?v=<?=VERSION ?>#iefix") format("embedded-opentype"),
		url("<?=SERV_URL ?>dist/css/flaticon/flaticon.woff?v=<?=VERSION ?>") format("woff"),
		url("<?=SERV_URL ?>dist/css/flaticon/flaticon.ttf?v=<?=VERSION ?>") format("truetype"),
		url("<?=SERV_URL ?>dist/css/flaticon/flaticon.svg?v=<?=VERSION ?>") format("svg");
		font-weight: normal;
		font-style: normal;
	}
	</style>
	<link rel="stylesheet" type="text/css" href="<?=SERV_URL ?>dist/css/flaticon/flaticon.css?v=<?=VERSION ?>"> 

    <!-- Custom styles for this template -->
    <link href="<?=SERV_URL ?>dist/css/signin.css?v=<?=VERSION ?>" rel="stylesheet">
    
    <!-- JQuery -->
    <script src="<?=SERV_URL ?>dist/js/jquery-3.3.1.min.js"></script>
  </head>

  <body>

    <div class="container">
		<div class="logo">
    	<img style="width:300px" src="<?=SERV_URL?>dist/images/logo_rosso.svg?v=<?=VERSION?>" alt="logo" />
    </div>
      <?php include $view; ?>

    </div> <!-- /container -->
  </body>
  <div id='ajax_loader' class="loading">
  	<div class="loading-inner">
  		<img src="<?=SERV_URL ?>dist/images/ajax-loader.gif" />
  	</div>
  </div>
</html>
