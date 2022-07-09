<?php
include "../app.config.php";
include "../libraries/commonfunctions.php";
include "../helpers/ez_sql_core.php";
include "../helpers/ez_sql_mysqli.php";
require_once "../libraries/acl.php";

//ini_set("display_errors",1);
$ACL = new ACL();
$db = null;
startup();

require_once CLASS_PATH . 'team.class.php';
require_once CLASS_PATH . 'log.class.php';



if ($_POST['csrfToken'] != $_SESSION['csrfToken' . $_POST['csrfTokenID']])
    exitWithError("U02", "CSRF Attack - Sessione scaduta, aggiorna la pagina");

$log = new Log(array("controller" => "team", "action" => $_POST['action']));


switch ($_POST['action']) {
    case "rimuoviUtente":
        team_requireLogin("rimuoviUtente");

        $team = new Team(array("id_team" => v_getPostVar("id_team", false)));

        $team->rimuoviUtente(v_getPostVar("username", false));

        $log->log(v_getPostVar("id_team", false) . ":" . v_getPostVar("username", false));

        if (!count($db->captured_errors)) {
            disconnectDB();
            die(json_encode(array(
                "error" => "0",
                "readableMsg" => "Utente rimosso"
            )));
        } else {
            exitWithError("CE03", "Si sono verificati i seguenti errori: <pre>" . print_r($db->captured_errors, true) . "</pre>");
        }
        break;
    case "aggiungiUtente":
        team_requireLogin("aggiungiUtente");

        $team = new Team(array("id_team" => v_getPostVar("id_team", false)));

        $team->aggiungiUtente(v_getPostVar("username", false));

        $log->log(v_getPostVar("id_team", false) . ":" . v_getPostVar("username", false));

        if (!count($db->captured_errors)) {
            disconnectDB();
            die(json_encode(array(
                "error" => "0",
                "readableMsg" => "Utente aggiunto"
            )));
        } else {
            exitWithError("CE03", "Si sono verificati i seguenti errori: <pre>" . print_r($db->captured_errors, true) . "</pre>");
        }
        break;
    case "aggiungiTeam":
        team_requireLogin("aggiungi");

        $team = new Team();

        $team->aggiungiTeam(v_getPostVar("nome", false));

        $log->log(v_getPostVar("nome", false));

        if (!count($db->captured_errors)) {
            disconnectDB();
            die(json_encode(array(
                "error" => "0",
                "readableMsg" => "Team aggiunto"
            )));
        } else {
            exitWithError("CE03", "Si sono verificati i seguenti errori: <pre>" . print_r($db->captured_errors, true) . "</pre>");
        }
        break;
    case "rimuoviTeam":
        team_requireLogin("rimuovi");

        $team = new Team(array("id_team" => v_getPostVar("id_team", false)));

        $team->rimuoviTeam();

        $log->log(v_getPostVar("id_team", false));

        if (!count($db->captured_errors)) {
            disconnectDB();
            die(json_encode(array(
                "error" => "0",
                "readableMsg" => "Team rimosso"
            )));
        } else {
            exitWithError("CE03", "Si sono verificati i seguenti errori: <pre>" . print_r($db->captured_errors, true) . "</pre>");
        }
        break;
    case "seleziona":
        unset($_SESSION['POST']);
        $_SESSION["POST"] = $_POST;
        die(json_encode(array(
            "error" => "0",
            "readableMsg" => ""
        )));
        break;
    default:
        team_requireLogin();
}
function team_requireLogin($action = "index")
{
    global $ACL;
    if (!$ACL->hasAccess("team", $action))
        exitWithError("U01", "Utente non autenticato");
}