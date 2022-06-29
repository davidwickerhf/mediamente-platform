<?php
include "app.config.php";
include "commonfunctions.php";
include "helpers/ez_sql_core.php";
include "helpers/ez_sql_mysqli.php";
include_once "helpers/ez_sql_oracle8_9.php";

date_default_timezone_set('Europe/Rome');
startup (); //in commonfunctions.php


// ////////////////////////////////////////////////////

// INIZIALIZZAZIONE URL REWRITE
$urlRequested = $_SERVER ['REQUEST_URI']; // Carico l'URL richiesto
$urlRequested = explode ( "/", $urlRequested ); // Explode in base alla barra
array_shift ( $urlRequested );

if (URL_BASE_PATH != "/")
	array_shift ( $urlRequested );

$urlRequested = filter_var_array ( $urlRequested, FILTER_SANITIZE_ENCODED ); // Sanitizzazione dell'Array
                                                                       
// Carico il controller
if (isset ( $urlRequested [0] ) && $urlRequested [0] != "")
	$CONTROLLER = $urlRequested [0];
else
	$CONTROLLER = "panoramica";
	
	// Carico l'azione del controller
if (isset ( $urlRequested [1] ) && $urlRequested [1] != "")
	$ACTION = $urlRequested [1];
else
	$ACTION = "index";
	
	// Carico eventuali parametri
$PARAMS = Array ();
for($i = 2; $i < count ( $urlRequested ); $i ++)
	$PARAMS [$i - 2] = $urlRequested [$i];
	// ////////////////////////////////////////////////////
	
	

set_include_path ( INCLUDE_PATH );

if (file_exists ( ROOT_PATH . "controller/" . $CONTROLLER . ".php" ))
	require (ROOT_PATH . "controller/" . $CONTROLLER . ".php");
else
	throw_404 ( "Controller " . $CONTROLLER . " inesistente" );

if (function_exists ( $ACTION ))
	$ACTION ( $PARAMS );
else
	throw_404 ( "Funzione " . $ACTION . " inesistente" );
	
	// include "layout/default.php";
if($_SERVER['REQUEST_URI']!=URL_BASE_PATH."utenti/login")
	$_SESSION['lastVisitedPage']=$_SERVER['REQUEST_URI'];
else
	$_SESSION['lastVisitedPage']=SERV_URL;

	
disconnectDB();
?>
