<?php
function index($params) {
	requireLogin (); // Richiedo login
 
	
	
	global $db; // uso il database
	            // Inizio corpo della funzione //////
	            

	$var_in_view ['pageTitle'] = "Progetto Turni" ;
	
    require_once CLASS_PATH.'progetto_gruppo_turni.class.php';
	
	$turni=new ProgettoGruppoTurni();
	
	$var_in_view['turni']=$turni->getElencoProgettiGruppoTurni();
    $var_in_view['lista_progetti']=$turni->getElencoProgetti();
    $var_in_view['lista_team']=$turni->getElencoTeam();
	
	
	$var_in_view ['printDebugQuery'] =  $db->captured_errors;
	// GENERO VIEW
	$view = ROOT_PATH . "views/turni/index.php"; // includo la view
	$layout = ROOT_PATH . "views/inc/default.php"; // includo il layout
	
	generate_view ( $view, $layout, $var_in_view );
}

function visualizza($params) {
    requireLogin (); // Richiedo login
    
    
    
    global $db; // uso il database
    // Inizio corpo della funzione //////
    
    
    require_once CLASS_PATH.'progetto_gruppo_turni.class.php';
    
    $turni=new ProgettoGruppoTurni(Array("id"=>$params[0]));
    
    if(!$turni->exists())
        throw_404();
    
    $var_in_view['turni']=$turni->getTurni();
      
    
    $var_in_view ['idGruppoTurno'] = $turni->getId();
    $var_in_view ['idProgetto'] = $turni->getIdProgetto();
    $var_in_view ['uniqsessid'] = uniqid();
    $var_in_view ['nomeGruppoTurno'] = $turni->getNome();
    $var_in_view ['pageTitle'] = "Turni ".$turni->getNome() ;
    $var_in_view ['printDebugQuery'] =  $db->captured_errors;
    
    $var_in_view ['openBozzaModal'] = ($params[1]=='generaBozza' ? true : false);
    // GENERO VIEW
    $view = ROOT_PATH . "views/turni/visualizza.php"; // includo la view
    $layout = ROOT_PATH . "views/inc/default.php"; // includo il layout
    
    generate_view ( $view, $layout, $var_in_view );
}


function aggiungiGruppoTurni($params) {
    requireLogin ("turni","aggiungiGruppoTurno"); // Richiedo login
    
    
    global $db; // uso il database
    // Inizio corpo della funzione //////
    
    require_once CLASS_PATH.'progetto_gruppo_turni.class.php';
    
    require_once CLASS_PATH.'cliente.class.php';
    $cliente=new Cliente();
    $var_in_view['clienti']=$cliente->getElencoClienti();
    
    if(isset($params[0]) && is_numeric($params[0]))
    {
        $gruppoTurni=new ProgettoGruppoTurni(Array(
            "id"=>$params[0]
        ));
        if(!$gruppoTurni->exists())
            throw_404();
            $var_in_view['progetto']=$gruppoTurni->getProgettoGruppoTurni();
            $var_in_view ['pageTitle'] = "Modifica gruppo turni ".$gruppoTurni->getNome();
            
        $var_in_view['id_cliente']=$gruppoTurni->getIdCliente();
    }
    else
    {
        $var_in_view['progetto']=null;
        $var_in_view ['pageTitle'] = "Aggiungi gruppo turni";
    }
    
    $var_in_view ['printDebugQuery'] =  $db->captured_errors;
    // GENERO VIEW
    $view = ROOT_PATH . "views/turni/aggiungiGruppoTurni.php"; // includo la view
    $layout = ROOT_PATH . "views/inc/default.php"; // includo il layout
    
    generate_view ( $view, $layout, $var_in_view );
}


function bozzaTurni($params) {
    requireLogin ("turni","bozzaTurni"); // Richiedo login
    
    
    global $db; // uso il database
    // Inizio corpo della funzione //////
        
    require_once CLASS_PATH.'turnistica.class.php';

    $var_in_view ['pageTitle'] = "Gestisci bozza turni" ;
    
    if(!isset($params[0]))
        throw_404();
    
    
    $var_in_view ['uniqsessid_bozza'] = $params[0];
    
    $turnistica = new Turnistica(Array('uniqsessid' => $params[0]));
        
    $turnistica->caricaBozza();
    
   // if($turnistica->getOwnerBozza() != getMyUsername()) 
   //     throw_404();
    
    $var_in_view['debugLog'] = $turnistica->getDebugLog();    
        
    
    $var_in_view ['printDebugQuery'] =  $db->captured_errors;
    // GENERO VIEW
    $view = ROOT_PATH . "views/turni/bozzaTurni.php"; // includo la view
    $layout = ROOT_PATH . "views/inc/default.php"; // includo il layout
    
    generate_view ( $view, $layout, $var_in_view );
}
