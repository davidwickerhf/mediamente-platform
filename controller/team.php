<?php
function index($params) {
	requireLogin (); // Richiedo login
 
	
	
	global $db; // uso il database
	            // Inizio corpo della funzione //////
	            

	$var_in_view ['pageTitle'] = "Team" ;
	
	require_once CLASS_PATH.'team.class.php';
	
	$team=new Team();
	
	$var_in_view['team']=$team->getElencoTeam();
	
	
	$var_in_view ['printDebugQuery'] =  $db->captured_errors;
	// GENERO VIEW
	$view = ROOT_PATH . "views/team/index.php"; // includo la view
	$layout = ROOT_PATH . "layout/default.php"; // includo il layout
	
	generate_view ( $view, $layout, $var_in_view );
}

function visualizza($params) {
    requireLogin (); // Richiedo login
    
    
    
    global $db; // uso il database
    // Inizio corpo della funzione //////
    
    
    require_once CLASS_PATH.'team.class.php';
    
    $team=new Team(Array("id_team"=>$params[0]));
    
    $var_in_view['team']=$team->getTeam();
    
    if(!$var_in_view['team'])
        throw_404();
    
    $var_in_view['utentiTeam']=$team->getUtentiTeam();
    
    
    require_once CLASS_PATH.'utente.class.php';

    $utente=new Utente();
    $var_in_view['utenti']=$utente->getElencoUtenti();
    
    
    
    
    $var_in_view ['pageTitle'] = "Team ".$team->getNome() ;
    $var_in_view ['printDebugQuery'] =  $db->captured_errors;
    // GENERO VIEW
    $view = ROOT_PATH . "views/team/visualizza.php"; // includo la view
    $layout = ROOT_PATH . "layout/default.php"; // includo il layout
    
    generate_view ( $view, $layout, $var_in_view );
}


function visualizzaAllocazione($params) {
    requireLogin (); // Richiedo login
    
    
    
    global $db; // uso il database
    // Inizio corpo della funzione //////
    
    
    require_once CLASS_PATH.'team.class.php';
	require_once CLASS_PATH . 'progetto.class.php';
	
    $progetto = new Progetto();
    if(isset($params[0]) && $params[0]!="0"){
		$team=new Team(Array("id_team"=>$params[0]));
		$var_in_view['id_team']=$params[0];
		$var_in_view['team']=$team->getTeam();
		$var_in_view['utentiTeam']=$team->getUtentiTeam();
		$var_in_view['progetti'] = $progetto->getElencoProgettiTeam($params[0]);
		$nameTeam=$team->getNome();
		$var_in_view['nameTeam']=$nameTeam;
	}
	else{
		$team=new Team(Array("id_team"=>"0"));
		$var_in_view['id_team']="0";
		$var_in_view['team']="On fly";
		$var_in_view['nameTeam']="On fly";
		$var_in_view['utentiTeam']=$team->getUtentiTeamByUsername(implode(",",$_SESSION["POST"]["codice_utente"]));
		$var_in_view['utenti']=implode(',',$_SESSION["POST"]["codice_utente"]);
		$var_in_view['progetti'] = $progetto->getElencoProgettiTeam("22");
		$nameTeam="On Fly";
	}
    
    if(!$var_in_view['team'])
        throw_404();
        
    //$var_in_view['utentiTeam']=$team->getUtentiTeam();
    //$var_in_view['team']=$team->getTeam();
    
    $var_in_view['anno']=filter_var($params[1],FILTER_SANITIZE_NUMBER_INT);
    $var_in_view['mese']=filter_var($params[2],FILTER_SANITIZE_NUMBER_INT);
    
    if (! $var_in_view['anno'])
        $var_in_view['anno'] = date("Y");
    
    if (! $var_in_view['mese'])
        $var_in_view['mese'] = date("m");

    
    //$var_in_view['progetti'] = $progetto->getElencoProgettiTeam($params[0]);
    
    $filtro = 'AND codice_cliente IN(';
    foreach ($var_in_view['progetti'] as $p)
        $filtro .= "'" . $p->codice_cliente . "',";
    
    $filtro = rtrim($filtro, ",") . ")";
    
    require_once CLASS_PATH . 'cliente.class.php';
    $cliente = new Cliente();
    $var_in_view['clienti'] = $cliente->getElencoClienti($filtro);
        
    
    $var_in_view ['pageTitle'] = "Allocazione team ".$nameTeam;
    $var_in_view ['printDebugQuery'] =  $db->captured_errors;
    // GENERO VIEW
    $view = ROOT_PATH . "views/team/visualizzaAllocazione.php"; // includo la view
    $layout = ROOT_PATH . "layout/default.php"; // includo il layout
        
    generate_view ( $view, $layout, $var_in_view );
}

function seleziona($params){
	requireLogin (); // Richiedo login
    
    
    
    global $db; // uso il database
    // Inizio corpo della funzione //////
	
	$var_in_view ['pageTitle'] = "Seleziona Team on fly";
	
	require_once CLASS_PATH.'team.class.php';
	
	$team=new Team();
	
	$var_in_view['utenti']=$team->getElencoUtenti();
	
	// GENERO VIEW
    $view = ROOT_PATH . "views/team/seleziona.php"; // includo la view
    $layout = ROOT_PATH . "layout/default.php"; // includo il layout
        
    generate_view ( $view, $layout, $var_in_view );
}
?>