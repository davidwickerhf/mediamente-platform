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

require_once CLASS_PATH . 'cliente.class.php';
require_once CLASS_PATH . 'log.class.php';
require_once CLASS_PATH . 'calendario.class.php';





if ($_POST['action'] != "login" && $_POST['csrfToken'] != $_SESSION['csrfToken' . $_POST['csrfTokenID']])
    exitWithError("U02", "CSRF Attack - Sessione scaduta, aggiorna la pagina");

$log = new Log(array("controller" => "cliente", "action" => $_POST['action']));

switch ($_POST['action']) {



    case "getProgettiCliente":
        cliente_requireLogin();

        $cliente = new Cliente(array("id" => v_getPostVar("id", false)));

        $calendario = new Calendario(array("id" => v_getPostVar('id_calendario', false)));

        /*
        if($calendario->getTipoCalendario()=='generico')
            $progetti=$cliente->getProgettiCalendario(v_getPostVar("id_calendario",false)); //" AND data_fine>=NOW() AND data_inizio<=NOW() "
        else
            $progetti=$cliente->getProgetti(); //" AND data_fine>=NOW() AND data_inizio<=NOW() "
        */
        //MODIFICA 2021-10-11 NEI FORM GIA' APERTI NON VENIVANO CARICATI I PROGETTI DOPO AVER SELEZIONATO UN ALTRO CLIENTE (TOLTO IF CONDITION, CARICHIAMO TUTTI I PROGETTI DEL CLIENTE)
        $progetti = $cliente->getProgetti();

        if (!count($db->captured_errors)) {
            disconnectDB();
            die(json_encode(array(
                "error" => "0",
                "readableMsg" => "",
                "progetti" => $progetti
            )));
        } else {
            exitWithError("CE03", "Si sono verificati i seguenti errori: <pre>" . print_r($db->captured_errors, true) . "</pre>");
        }
        break;

    case "getCommesseCliente":
        cliente_requireLogin();

        $cliente = new Cliente(array("id" => v_getPostVar("id", false)));
        $commesse = $cliente->getCommesse(" AND valid_to>=NOW() AND valid_from<=NOW() ");

        if (!count($db->captured_errors)) {
            disconnectDB();
            die(json_encode(array(
                "error" => "0",
                "readableMsg" => "",
                "commesse" => $commesse
            )));
        } else {
            exitWithError("CE03", "Si sono verificati i seguenti errori: <pre>" . print_r($db->captured_errors, true) . "</pre>");
        }
        break;

    default:
        cliente_requireLogin();
}
function cliente_requireLogin($action = "index")
{
    global $ACL;
    if (!$ACL->hasAccess("cliente", $action))
        exitWithError("U01", "Utente non autenticato");
}