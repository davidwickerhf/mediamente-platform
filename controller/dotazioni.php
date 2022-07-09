<?php
function index($params) {
	requireLogin (); // Richiedo login
 
	
	
	global $db; // uso il database
	            // Inizio corpo della funzione //////
	            

	$var_in_view ['pageTitle'] = "Dotazioni aziendali" ;
	
	require_once CLASS_PATH.'dotazione.class.php';
	
	$dotazione=new Dotazione();
	
	$var_in_view['dotazioni']=$dotazione->getDotazioniPersonali();
	
	
    $json= Array(
        "method"=> "getEndpointsList",
        "params"=> Array(
			"perPage"=> 100
		),
        "jsonrpc"=> "2.0",
        "id"=>uniqid()
    );
    
    $ch = curl_init("https://cloudgz.gravityzone.bitdefender.com/api/v1.0/jsonrpc/network");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    #curl_setopt($ch, CURLOPT_USERPWD, "bb5eadb7e28f3f5fcccad4b4461507553e74ff697966bf15c7712fd5510edc9c" . ":" ); #VECCHIA API / SAVERIO
    curl_setopt($ch, CURLOPT_USERPWD, "3c45cd9db584790a6b425aa5d441baad97842ba3cb5d5c5dc7a572d768fade56" . ":" ); #PITANNI
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($json));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $return = curl_exec($ch);
    curl_close($ch);
	    
	$av=json_decode($return);
	
	$db->query("TRUNCATE TABLE antivirus");
	
	foreach($av->result->items as $a)
	   $db->query("INSERT INTO antivirus(fqdn,isManaged) VALUES('".$a->fqdn."','".$a->isManaged."') ON DUPLICATE KEY UPDATE isManaged=VALUES(isManaged)");
	
	
    $var_in_view['antivirusInstallati']=Array();
	foreach($db->get_results("SELECT * FROM antivirus WHERE isManaged=1") as $a)
	    array_push($var_in_view['antivirusInstallati'],$a->fqdn);
	
	$var_in_view ['printDebugQuery'] =  $db->captured_errors;
	// GENERO VIEW
	$view = ROOT_PATH . "views/dotazioni/index.php"; // includo la view
	$layout = ROOT_PATH . "views/inc/default.php"; // includo il layout
	
	generate_view ( $view, $layout, $var_in_view );
}
