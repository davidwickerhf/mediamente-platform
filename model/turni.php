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

require_once CLASS_PATH . 'progetto_gruppo_turni.class.php';
require_once CLASS_PATH . 'turnistica.class.php';
require_once CLASS_PATH . 'log.class.php';



if ($_POST['csrfToken'] != $_SESSION['csrfToken' . $_POST['csrfTokenID']])
    exitWithError("U02", "CSRF Attack - Sessione scaduta, aggiorna la pagina");

$log = new Log(array("controller" => "turni", "action" => $_POST['action']));


switch ($_POST['action']) {
    case "rimuoviGruppoTurno":
        turno_requireLogin("rimuoviGruppoTurno");

        $turno = new ProgettoGruppoTurni(array("id" => v_getPostVar("id_turno", false)));

        $turno->rimuoviProgettoGruppoTurni(v_getPostVar("username", false));

        $log->log(v_getPostVar("id_turno", false) . ":" . v_getPostVar("username", false));

        if (!count($db->captured_errors)) {
            disconnectDB();
            die(json_encode(array(
                "error" => "0",
                "readableMsg" => "Progetto Gruppo Turno rimosso"
            )));
        } else {
            exitWithError("CE03", "Si sono verificati i seguenti errori: <pre>" . print_r($db->captured_errors, true) . "</pre>");
        }
        break;
    case "aggiungiGruppoTurno":
        turno_requireLogin("aggiungiGruppoTurno");



        if (!v_getPostVar("id", false)) {
            $turno = new ProgettoGruppoTurni();
            $id = $turno->aggiungiProgettoGruppoTurno();
        } else {
            $turno = new ProgettoGruppoTurni(array("id" => v_getPostVar("id", false)));
            $id = v_getPostVar("id", false);
        }

        $values = array(
            "nome" => v_getPostVar("nome", false),
            "id_progetto" => v_getPostVar("id_progetto", false),
            "id_team" => v_getPostVar("id_team", false),
            "priorita" => v_getPostVar("priorita", false),
            "tipo_turni" => v_getPostVar("tipo_turni", false),
            "giorno_inizio" => v_getPostVar("giorno_inizio", false),
            "alloca_utente" => v_getPostVar("alloca_utente", false) == "true" ? 1 : 0
        );

        if (!$turno->salvaProgettoGruppoTurno($values) && !v_getPostVar("id", false))
            $turno->rimuoviProgettoGruppoTurni();


        if (!count($db->captured_errors)) {
            disconnectDB();
            die(json_encode(array(
                "error" => "0",
                "readableMsg" => "Gruppo turni aggiunto",
                "id" => $id
            )));
        } else {
            exitWithError("CE03", "Si sono verificati i seguenti errori: <pre>" . print_r($db->captured_errors, true) . "</pre>");
        }
        break;
    case "rimuoviTurno":
        turno_requireLogin("rimuoviTurno");

        $turno = new ProgettoGruppoTurni();

        $turno->rimuoviTurno(v_getPostVar("id_turno", false));

        $log->log(v_getPostVar("id_turno", false) . ":" . v_getPostVar("username", false));

        if (!count($db->captured_errors)) {
            disconnectDB();
            die(json_encode(array(
                "error" => "0",
                "readableMsg" => "Progetto Gruppo Turno rimosso"
            )));
        } else {
            exitWithError("CE03", "Si sono verificati i seguenti errori: <pre>" . print_r($db->captured_errors, true) . "</pre>");
        }
        break;
    case "aggiungiTurno":
        turno_requireLogin("aggiungiTurno");

        $turno = new ProgettoGruppoTurni(array("id" => v_getPostVar("idGruppoTurno", false)));

        $values = array(
            "id_gruppo" => v_getPostVar("idGruppoTurno", false),
            "nome" => v_getPostVar("nome", false),
            "giorno" => v_getPostVar("giorno", false),
            "inizio" => v_getPostVar("inizio", false),
            "fine" => v_getPostVar("fine", false),
            "inizio_festivo" => v_getPostVar("inizio_festivo", false),
            "fine_festivo" => v_getPostVar("fine_festivo", false)
        );

        $turno->aggiungiTurno($values);

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
    case "generaBozzaTurni":
        turno_requireLogin("generaBozzaTurni");


        $values = array(
            "id_gruppo" => v_getPostVar("idGruppoTurno", false),
            "prima_settimana" => v_getPostVar("prima_settimana", false),
            "ultima_settimana" => v_getPostVar("ultima_settimana", false),
            "uniqsessid" => v_getPostVar("uniqsessid", false)
        );

        $turnistica = new Turnistica(array('uniqsessid' => $values['uniqsessid']));

        $turnistica->enableDebug("VAR");

        $turnistica->aggiungiBozza();

        $turnistica->setProgetto($values['id_gruppo']);

        if ($values['ultima_settimana']) {

            if ($values['ultima_settimana'] <= $values['prima_settimana'])
                exitWithError("CE03", "Impossibile generare il calendario con questi parametri: <pre>" . print_r($values, true) . "</pre>");

            for ($i = $values['prima_settimana']; $i <= $values['ultima_settimana']; $i++) {
                $turnistica->setSettimana($i, 2022);

                $turnistica->pianificaSettimana();
            }
        } else {
            $turnistica->setSettimana($values['prima_settimana'], 2022);

            $turnistica->pianificaSettimana();
        }


        if (!count($db->captured_errors)) {
            disconnectDB();
            die(json_encode(array(
                "error" => "0",
                "readableMsg" => "Bozza generata"
            )));
        } else {
            exitWithError("CE03", "Si sono verificati i seguenti errori: <pre>" . print_r($db->captured_errors, true) . "</pre>");
        }
        break;
    case "approvaBozzaTurni":
        turno_requireLogin("approvaBozzaTurni");


        $values = array(
            "uniqsessid_bozza" => v_getPostVar("uniqsessid_bozza", false)
        );

        $turnistica = new Turnistica(array('uniqsessid' => $values['uniqsessid_bozza']));

        $turnistica->caricaBozza();

        $turnistica->approvaBozza();


        if (!count($db->captured_errors)) {
            disconnectDB();
            die(json_encode(array(
                "error" => "0",
                "readableMsg" => "Bozza approvata e salvata nel calendario"
            )));
        } else {
            exitWithError("CE03", "Si sono verificati i seguenti errori: <pre>" . print_r($db->captured_errors, true) . "</pre>");
        }
        break;
    case "rimuoviBozzaTurni":
        turno_requireLogin("rimuoviBozzaTurni");


        $values = array(
            "uniqsessid_bozza" => v_getPostVar("uniqsessid_bozza", false)
        );

        $turnistica = new Turnistica(array('uniqsessid' => $values['uniqsessid_bozza']));


        $turnistica->caricaBozza();

        $turnistica->eliminaTabellaTemporanea();


        if (!count($db->captured_errors)) {
            disconnectDB();
            die(json_encode(array(
                "error" => "0",
                "readableMsg" => "Bozza eliminata"
            )));
        } else {
            exitWithError("CE03", "Si sono verificati i seguenti errori: <pre>" . print_r($db->captured_errors, true) . "</pre>");
        }
        break;
    default:
        turno_requireLogin();
}
function turno_requireLogin($action = "index")
{
    global $ACL;
    if (!$ACL->hasAccess("turni", $action))
        exitWithError("U01", "Utente non autenticato");
}
