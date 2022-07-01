<?php
include "../app.config.php";
include "../commonfunctions.php";
include "../helpers/ez_sql_core.php";
include "../helpers/ez_sql_mysqli.php";
require_once "../acl.php";

//ini_set("display_errors",1);
$ACL=new ACL();
$db = null;
startup ();

require_once CLASS_PATH.'progetto.class.php';
require_once CLASS_PATH.'log.class.php';





if($_POST ['action']!="login" && $_POST ['csrfToken']!=$_SESSION['csrfToken'.$_POST['csrfTokenID']])
	exitWithError("U02","CSRF Attack - Sessione scaduta, aggiorna la pagina");

$log=new Log(Array("controller"=>"progetto","action"=>$_POST ['action']));

switch ($_POST ['action']) {
	
    case "aggiungiProgetto":
        progetto_requireLogin ("aggiungi");
        
        if(!v_getPostVar("id",false))
        {
        $progetto=new Progetto();
        $id=$progetto->aggiungiProgetto();
        }
        else
            $progetto=new Progetto(Array("id"=>v_getPostVar("id",false)));
        
        $values=            Array(
            "nome" => v_getPostVar("nome",false),
            "id_calendario" => v_getPostVar("id_calendario",false),
            "referente" => v_getPostVar("referente",false),
            "codice_cliente" => v_getPostVar("codice_cliente",false),
            "id_commessa" => v_getPostVar("id_commessa",false),
            "data_inizio" => v_getPostVar("data_inizio",false),
            "data_fine" => v_getPostVar("data_fine",false),
            "priorita" => v_getPostVar("priorita",false),
            );
        
        if(!$progetto->salvaProgetto(
            $values
            ) && !v_getPostVar("id",false))
            $progetto->rimuoviProgetto();
        
        if(v_getPostVar("id",false))
            $id=v_getPostVar("id",false);
        
        $log->log(json_encode($values));
        
        if (! count ( $db->captured_errors )) {
            disconnectDB();
            setFlash("<div class='alert alert-success'>Modifiche apportate con successo.</div>");
            die ( json_encode ( Array (
                "error" => "0",
                "readableMsg" => "",
                "id"=>$id
                ) ) );
        } else {
            exitWithError ( "CE03", "Si sono verificati i seguenti errori: <pre>" . print_r ( $db->captured_errors, true ) . "</pre>" );
        }
        break;
    case "rimuoviProgetto":
        progetto_requireLogin ("rimuovi");
        
        $progetto=new Progetto(Array("id"=>v_getPostVar("id",false)));
        
        $progetto->rimuoviProgetto();
        
        $log->log(v_getPostVar("id",false));
        
        if (! count ( $db->captured_errors )) {
            disconnectDB();
            die ( json_encode ( Array (
                "error" => "0",
                "readableMsg" => "progetto rimosso"
                ) ) );
        } else {
            exitWithError ( "CE03", "Si sono verificati i seguenti errori: <pre>" . print_r ( $db->captured_errors, true ) . "</pre>" );
        }
        break;
    
    case "getUtentiProgetto":
        progetto_requireLogin ();
        
        $progetto=new Progetto(Array("id"=>v_getPostVar("id",false)));
        
        $users=$progetto->getUtenti();
        
        if (! count ( $db->captured_errors )) {
            disconnectDB();
            die ( json_encode ( Array (
                "error" => "0",
                "readableMsg" => "",
                "users"=>$users
                ) ) );
        } else {
            exitWithError ( "CE03", "Si sono verificati i seguenti errori: <pre>" . print_r ( $db->captured_errors, true ) . "</pre>" );
        }
    break;
    case "getTeamProgetto":
        progetto_requireLogin ();
        
        $progetto=new Progetto(Array("id"=>v_getPostVar("id",false)));
        
        $team=$progetto->getTeam();
        
        if (! count ( $db->captured_errors )) {
            disconnectDB();
            die ( json_encode ( Array (
                "error" => "0",
                "readableMsg" => "",
                "team"=>$team
            ) ) );
        } else {
            exitWithError ( "CE03", "Si sono verificati i seguenti errori: <pre>" . print_r ( $db->captured_errors, true ) . "</pre>" );
        }
        break;
    case "aggiungiTeam":
        progetto_requireLogin ("aggiungiTeam");
        
        $progetto=new Progetto(Array("id"=>v_getPostVar("id",false)));
        
        $progetto->aggiungiTeam(v_getPostVar("id_team",false));
        
        if (! count ( $db->captured_errors )) {
            disconnectDB();
            die ( json_encode ( Array (
                "error" => "0",
                "readableMsg" => "",
                ) ) );
        } else {
            exitWithError ( "CE03", "Si sono verificati i seguenti errori: <pre>" . print_r ( $db->captured_errors, true ) . "</pre>" );
        }
        break;
    case "rimuoviTeam":
        progetto_requireLogin ("rimuoviTeam");
        
        $progetto=new Progetto(Array("id"=>v_getPostVar("id",false)));
        
        $progetto->rimuoviTeam(v_getPostVar("id_team",false));
        
        if (! count ( $db->captured_errors )) {
            disconnectDB();
            die ( json_encode ( Array (
                "error" => "0",
                "readableMsg" => "",
                ) ) );
        } else {
            exitWithError ( "CE03", "Si sono verificati i seguenti errori: <pre>" . print_r ( $db->captured_errors, true ) . "</pre>" );
        }
        break;
	default :
	    progetto_requireLogin ();
		
	
}
function progetto_requireLogin($action="index") {
		global $ACL;
		if (! $ACL->hasAccess("progetto",$action))
			exitWithError ( "U01", "Utente non autenticato" );
}
