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
	            

	$var_in_view ['pageTitle'] = "Rapportinator" ;
	
	
	
	$var_in_view ['printDebugQuery'] =  $db->captured_errors;
	// GENERO VIEW
	$view = ROOT_PATH . "views/rapportinator/index.php"; // includo la view
	$layout = ROOT_PATH . "views/inc/default.php"; // includo il layout
	
	generate_view ( $view, $layout, $var_in_view );
}


function download($params)
{
    requireLogin();
    
    $uniqueid=$params[0];
    
    $file=ROOT_PATH.'documenti/'.getMyUsername().'-'.$uniqueid.'.zip';
    
    if(!file_exists($file))
        throw_404();
    
    header('Content-type:  application/zip');
    header('Content-Length: ' . filesize($file));
    header('Content-Disposition: attachment; filename="'.getMyUsername().'-'.$uniqueid.'.zip"');
    readfile($file);
    unlink($file);
    
    ignore_user_abort(true);
    if (connection_aborted()) {
        unlink($file);
    }
}
