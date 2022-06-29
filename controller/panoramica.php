<?php
function index($params) {
	requireLogin (); // Richiedo login
	
	if(isset($_SESSION['return']) && $_SESSION['return'])
	{
		header("Location:".$_SESSION['return']);
		unset($_SESSION['return']);
		die();
	}
  
	
	
	global $db; // uso il database
	            // Inizio corpo della funzione //////
	            

	$var_in_view ['pageTitle'] = "Panoramica" ;
	
	
	
	$var_in_view ['printDebugQuery'] =  $db->captured_errors;
	// GENERO VIEW
	$view = ROOT_PATH . "views/panoramica/index.php"; // includo la view
	$layout = ROOT_PATH . "layout/default.php"; // includo il layout
	
	generate_view ( $view, $layout, $var_in_view );
}
?>