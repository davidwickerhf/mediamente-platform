<?php
include "../app.config.php";
include "../commonfunctions.php";
include "../helpers/ez_sql_core.php";
include "../helpers/ez_sql_mysqli.php";
require_once "../acl.php";

//ini_set("display_errors",1);
$ACL = new ACL();
$db = null;
startup();

require_once CLASS_PATH . 'calendario.class.php';
require_once CLASS_PATH . 'log.class.php';
require_once CLASS_PATH . 'http.class.php';

if ($_POST['action'] != "login" && $_POST['csrfToken'] != $_SESSION['csrfToken' . $_POST['csrfTokenID']])
    exitWithError("U02", "CSRF Attack - Sessione scaduta, aggiorna la pagina");

$log = new Log(Array(
    "controller" => "calendario",
    "action" => $_POST['action']
));

switch ($_POST['action']) {
    case "getFestivita":
        calendario_requireLogin();
        
        $start = date("Y-m-d H:i:s", strtotime(v_getPostVar('start', false)));
        $end = date("Y-m-d H:i:s", strtotime(v_getPostVar('end', false)));
        
        $calendario = new Calendario(Array(
            "start" => $start,
            "end" => $end
            ));
        
        $turni = $calendario->getFestivita();
        
        $out = json_encode($calendario->formattaTurni($turni));
        
        if (! count($db->captured_errors)) {
            disconnectDB();
            die($out);
        } else {
            exitWithError("E28", "Si sono verificati i seguenti errori: <pre>" . print_r($db->captured_errors, true) . "</pre>");
        }
        
        break;
    case "getTurni":
        calendario_requireLogin();
        
        $start = date("Y-m-d H:i:s", strtotime(v_getPostVar('start', false)));
        $end = date("Y-m-d H:i:s", strtotime(v_getPostVar('end', false)));
        
        $calendario = new Calendario(Array(
            "id" => v_getPostVar('id', false),
            "start" => $start,
            "end" => $end
        ));
        
        $turni = $calendario->getTurni();
        
        $out = json_encode($calendario->formattaTurni($turni));
        
        if (! count($db->captured_errors)) {
            disconnectDB();
            die($out);
        } else {
            exitWithError("E28", "Si sono verificati i seguenti errori: <pre>" . print_r($db->captured_errors, true) . "</pre>");
        }
        
        break;
    case "aggiungiCalendario":
        calendario_requireLogin("aggiungi");
        
        if (! v_getPostVar("id", false)) {
            $calendario = new Calendario();
            $calendario->aggiungiCalendario();
        } else
            $calendario = new Calendario(Array(
                "id" => v_getPostVar("id", false)
            ));
        
        $values = Array(
            "nome" => v_getPostVar("nome", false),
            "colore" => v_getPostVar("colore", false),
            "colore_testo" => v_getPostVar("colore_testo", false)
        );
        
        if (! $calendario->salvaCalendario($values) && ! v_getPostVar("id", false))
            $calendario->rimuoviCalendario();
        
        $log->log(v_getPostVar("nome", false));
        
        if (! count($db->captured_errors)) {
            disconnectDB();
            setFlash("<div class='alert alert-success'>Modifiche apportate con successo.</div>");
            die(json_encode(Array(
                "error" => "0",
                "readableMsg" => ""
            )));
        } else {
            exitWithError("CE03", "Si sono verificati i seguenti errori: <pre>" . print_r($db->captured_errors, true) . "</pre>");
        }
        break;
    case "rimuoviCalendario":
        calendario_requireLogin("rimuovi");
        
        $calendario = new Calendario(Array(
            "id" => v_getPostVar("id", false)
        ));
        
        $calendario->rimuoviCalendario();
        
        $log->log(v_getPostVar("id", false));
        
        if (! count($db->captured_errors)) {
            disconnectDB();
            die(json_encode(Array(
                "error" => "0",
                "readableMsg" => "calendario rimosso"
            )));
        } else {
            exitWithError("CE03", "Si sono verificati i seguenti errori: <pre>" . print_r($db->captured_errors, true) . "</pre>");
        }
        break;
    case "aggiungiCalendarioEsterno":
        calendario_requireLogin();
        
        $calendario = new Calendario();
        
        if (! v_getPostVar("id", false)) {
            $id=$calendario->aggiungiCalendarioEsterno();
        } 
        else 
            $id=v_getPostVar("id", false);
        
        $values = Array(
            "id" => $id,
            "nome" => v_getPostVar("nome", false),
            "url" => v_getPostVar("url", false),
            "username"=>getMyUsername()
        );
        
        if (! $calendario->salvaCalendarioEsterno($values) && ! v_getPostVar("id", false))
            $calendario->rimuoviCalendarioEsterno($id);
        
        $log->log(v_getPostVar("nome", false));
        
        if (! count($db->captured_errors)) {
            disconnectDB();
            setFlash("<div class='alert alert-success'>Modifiche apportate con successo.</div>");
            die(json_encode(Array(
                "error" => "0",
                "readableMsg" => ""
            )));
        } else {
            exitWithError("CE03", "Si sono verificati i seguenti errori: <pre>" . print_r($db->captured_errors, true) . "</pre>");
        }
        break;
    case "rimuoviCalendarioEsterno":
        calendario_requireLogin();
        
        $calendario = new Calendario();
        
        $calendario->rimuoviCalendarioEsterno(v_getPostVar("id", false));
        
        $log->log(v_getPostVar("id", false));
        
        if (! count($db->captured_errors)) {
            disconnectDB();
            die(json_encode(Array(
                "error" => "0",
                "readableMsg" => "calendario rimosso"
                )));
        } else {
            exitWithError("CE03", "Si sono verificati i seguenti errori: <pre>" . print_r($db->captured_errors, true) . "</pre>");
        }
        break;
    case "sincronizzaCalendariEsterni":
        calendario_requireLogin();
        
        $calendario = new Calendario();
        
        $calendario->importaCalendariEsterni(" AND username='".getMyUsername()."'");
        
        
        if (! count($db->captured_errors)) {
            disconnectDB();
            die(json_encode(Array(
                "error" => "0",
                "readableMsg" => ""
                )));
        } else {
            exitWithError("CE03", "Si sono verificati i seguenti errori: <pre>" . print_r($db->captured_errors, true) . "</pre>");
        }
        break;
    case "aggiungiEvento":
        calendario_requireLogin("aggiungiEvento");
        
        $inizio = v_getPostVar("inizio", false);
        $fine = v_getPostVar("fine", false);
        
        if (strtotime($inizio) == 0)
            exitWithError("C04", "Data inzio non valida");
        if (strtotime($fine) == 0)
            exitWithError("C05", "Data fine non valida");
        if (strtotime($fine) < strtotime($inizio))
            exitWithError("C06", "Data fine antecedente a data inizio");
        
        
        if (date("Y-m-d", strtotime($fine)) == $fine)
            $fine = $fine . " 23:59:59";
        
        $inizio = date("Y-m-d H:i:s", strtotime($inizio));
        $fine = date("Y-m-d H:i:s", strtotime($fine));
		$ricorsivo = str_replace("false", "0", str_replace("true", "1",v_getPostVar("ricorsivo", false)));
        
        $calendario = new Calendario(Array(
            "start"=>$inizio,
            "end"=>$fine,
            "id" => v_getPostVar("id_calendario", false)
        ));
        
        if(v_getPostVar("forza_inserimento", false)==0)
        {
            $sovrapposizioni=$calendario->getSovrapposizioni($inizio,$fine,v_getPostVar("username", false),v_getPostVar("id", false),$ricorsivo);
            $festivita=$calendario->getFestivita();
            if(count($sovrapposizioni) || count($festivita))
            {
                disconnectDB();
                header ( "HTTP/1.0 400 Bad Request" );
                header ( "Content-Type: application/json", true );
                die(json_encode(Array(
                    "error" => 'C629',
                    "readableMsg" => "Sono presenti sovrapposizioni per ".v_getPostVar("username", false)." in questa fascia oraria",
                    "sovrapposizioni" => $sovrapposizioni,
                    "festivita"=>$festivita
                    )));
            }
        }
        
        if (v_getPostVar("id", false) == 0)
        {
            $id_evento = $calendario->aggiungiEvento();
        }
        else
            $id_evento = v_getPostVar("id", false);
        
        $values = Array(
            "id" => $id_evento,
            "id_calendario" => v_getPostVar("id_calendario", false),
            "titolo" => v_getPostVar("titolo", false),
            "descrizione" => v_getPostVar("descrizione", false),
            "inizio" => $inizio,
            "fine" => $fine,
            "id_progetto" => v_getPostVar("id_progetto", false),
            "username" => v_getPostVar("username", false),
            "bloccante" => v_getPostVar("bloccante", false)=="true" ? 1:0,
			"ricorsivo" => $ricorsivo,
			"inserito_da" => getMyUsername()
        );
        
        $calendario->salvaEvento($values);
        
        $turno = $calendario->getTurno($id_evento);
        
        $out = $calendario->formattaTurno($turno);
        
        $log->log(json_encode($values));
        
        if (! count($db->captured_errors)) {
            disconnectDB();
            die(json_encode(Array(
                "error" => "0",
                "readableMsg" => "",
                "event" => $out
            )));
        } else {
            exitWithError("CE03", "Si sono verificati i seguenti errori: <pre>" . print_r($db->captured_errors, true) . "</pre>");
        }
        break;
    case "rimuoviEvento":
        calendario_requireLogin("rimuoviEvento");
        
        
        $calendario = new Calendario();
        
        $calendario->rimuoviEvento(v_getPostVar("id",false));
        
        $log->log(json_encode(v_getPostVar("id",false)));
       
        
        if (! count($db->captured_errors)) {
            disconnectDB();
            die(json_encode(Array(
                "error" => "0",
                "readableMsg" => ""
            )));
        } else {
            exitWithError("CE03", "Si sono verificati i seguenti errori: <pre>" . print_r($db->captured_errors, true) . "</pre>");
        }
        break;
    case "getOccupazione":
        calendario_requireLogin();
		
		$postVarTeam=v_getPostVar("idteam",false);
		$postCodiceUtente=v_getPostVar("utenti",false);
		
		require_once CLASS_PATH.'team.class.php';
        if(isset($postVarTeam) && $postVarTeam!=0){
			$team=new Team(Array("id_team"=>$postVarTeam));
			$utenti=$team->getUtentiTeam();
		}else{
			$team=new Team();
			$utenti=$team->getUtentiTeamByUsername($postCodiceUtente);
		}
        $meseanno=v_getPostVar("mese",false);
        
        $giorniMese=date("t",strtotime($meseanno."-01"));
        
        $out=Array();
        foreach ($utenti as $u)
        {
            for ($i = 1; $i <= $giorniMese; $i ++) {
                $data = $meseanno . "-" . ($i < 10 ? "0" . $i : $i);
                
                $calendario = new Calendario(Array(
                    "start" => $data . " 09:00:00",
                    "end" => $data . " 12:59:59",
                    "titleFormat"=>"projectName"
                ));
                $calendario->setUsername($u->username);
                
                $turni=$calendario->getTurni(" AND id_progetto IS NOT NULL ");
                if (count($turni))
                    $row['M'] = $calendario->formattaTurni($turni);
                else
                    $row['M'] = "false";
                
                $calendario = new Calendario(Array(
                    "start" => $data . " 13:30:01",
                    "end" => $data . " 23:59:59",
                    "titleFormat"=>"projectName"
                ));
                $calendario->setUsername($u->username);
                
                $turni=$calendario->getTurni(" AND id_progetto IS NOT NULL ");
                if (count($turni))
                    $row['P'] = $calendario->formattaTurni($turni);
                else
                    $row['P'] = "false";
                
                $out[$u->username][$data] = $row;
            }
           
        }
        
            
        if (! count($db->captured_errors)) {
            disconnectDB();
            die(json_encode(Array(
                "error" => "0",
                "readableMsg" => "",
				"idteam" => $postVarTeam,
				"CodiceUtente" => $postCodiceUtente,
                "users"=>$out
                )));
        } else {
            exitWithError("CE03", "Si sono verificati i seguenti errori: <pre>" . print_r($db->captured_errors, true) . "</pre>");
        }
        break;
    case 'esporta':
        calendario_requireLogin();
        unset($_SESSION['POST']);
        $_SESSION["POST"] = $_POST;
        die(json_encode(Array(
            "error" => "0",
            "readableMsg" => ""
            )));
        break;
    default:
        calendario_requireLogin();
}

function calendario_requireLogin($action = "index")
{
    global $ACL;
    if (! $ACL->hasAccess("calendario", $action))
        exitWithError("U01", "Utente non autenticato");
}
