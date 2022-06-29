<?php
function index($params) {
	requireLogin (); // Richiedo login
	

	global $db; // uso il database
	            // Inizio corpo della funzione //////
	
	require_once CLASS_PATH.'progetto.class.php';

	
	$progetto=new Progetto();
	$var_in_view['progetti']=$progetto->getElencoProgetti();

	
	$var_in_view ['pageTitle'] = "Progetti" ;
	
	$var_in_view ['printDebugQuery'] =  $db->captured_errors;
	// GENERO VIEW
	$view = ROOT_PATH . "views/progetto/index.php"; // includo la view
	$layout = ROOT_PATH . "layout/default.php"; // includo il layout
	
	generate_view ( $view, $layout, $var_in_view );
}


function visualizza($params) {
    requireLogin (); // Richiedo login
    
    
    global $db; // uso il database
    // Inizio corpo della funzione //////
    
    require_once CLASS_PATH.'progetto.class.php';
    
    
    if(isset($params[0]) && is_numeric($params[0]))
    {
        $progetto=new Progetto(Array(
            "id"=>$params[0]
            ));
        $var_in_view['progetto']=$progetto->getProgetto();
        $var_in_view['progetto_team']=$progetto->getTeam();
    }
    else 
        throw_404();
    
        
    require_once CLASS_PATH.'team.class.php';
    $team=new Team();
    $var_in_view['team']=$team->getElencoTeam();
    
    require_once CLASS_PATH.'cliente.class.php';
    $cliente=new Cliente(Array("id"=> $var_in_view['progetto']->{'codice_cliente'}));
    $var_in_view['cliente']=$cliente->getCliente();
    
    $var_in_view['commessa']=$cliente->getCommessa($var_in_view['progetto']->{'id_commessa'});
    
    
    require_once CLASS_PATH.'calendario.class.php';
    $calendario=new Calendario(Array(
        "id"=>$var_in_view['progetto']->{'id_calendario'}
        ));
    $var_in_view['calendario']=$calendario->getCalendario();
    
    
    $var_in_view ['pageTitle'] = "[".$var_in_view['cliente']->{'nome'}."] Progetto ".$progetto->getNome() ;
    
    $var_in_view ['printDebugQuery'] =  $db->captured_errors;
    // GENERO VIEW
    $view = ROOT_PATH . "views/progetto/visualizza.php"; // includo la view
    $layout = ROOT_PATH . "layout/default.php"; // includo il layout
    
    generate_view ( $view, $layout, $var_in_view );
}


function aggiungi($params) {
    requireLogin ("progetto","aggiungi"); // Richiedo login
    
    
    global $db; // uso il database
    // Inizio corpo della funzione //////
    
    require_once CLASS_PATH.'progetto.class.php';
    require_once CLASS_PATH.'calendario.class.php';
    require_once CLASS_PATH.'utente.class.php';
    require_once CLASS_PATH.'cliente.class.php';
    
    $calendario=new Calendario();
    $var_in_view['calendari']=$calendario->getElencoCalendari(" AND tipo='generico'");
    
    
    $utente=new Utente();
    $var_in_view['utenti']=$utente->getElencoUtenti();
    
    
    $cliente=new Cliente();
    $var_in_view['clienti']=$cliente->getElencoClienti();
    
    
    if(isset($params[0]) && is_numeric($params[0]))
    {
        $progetto=new Progetto(Array(
            "id"=>$params[0]
            ));
        if(!$progetto->exists())
            throw_404();
        $var_in_view['progetto']=$progetto->getProgetto();
        $var_in_view ['pageTitle'] = "Modifica progetto ".$progetto->getNome();
    }
    else
    {
        $var_in_view['progetto']=null;
        $var_in_view ['pageTitle'] = "Aggiungi progetto";
    }
        
    
    
    $var_in_view ['printDebugQuery'] =  $db->captured_errors;
    // GENERO VIEW
    $view = ROOT_PATH . "views/progetto/aggiungi.php"; // includo la view
    $layout = ROOT_PATH . "layout/default.php"; // includo il layout
    
    generate_view ( $view, $layout, $var_in_view );
}


?>