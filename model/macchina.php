<?php
// include "../app.config.php";
// include "../commonfunctions.php";
// include "../helpers/ez_sql_core.php";
// include "../helpers/ez_sql_mysqli.php";
// require_once "../acl.php";

// //ini_set("display_errors",1);
// $ACL = new ACL();
// $db = null;
// startup();

// require_once CLASS_PATH . 'log.class.php';

// if ($_POST['csrfToken'] != $_SESSION['csrfToken' . $_POST['csrfTokenID']])
//     exitWithError("U02", "CSRF Attack - Sessione scaduta, aggiorna la pagina");

// $log = new Log(array("controller" => "team", "action" => $_POST['action']));


// switch ($_POST['action']) {
//     case "rimuoviUtente":
//         break;
// }

class Macchina
{
    private ezSQL_mysqli $db;

    public function __construct()
    {
        global $db;
        startup();
        $this->db = $db;
    }

    // SECTION: Methods relative to the management of cars
    public function register()
    {
        throw new Exception('Not implemented');
    }

    public function archive()
    {
        throw new Exception('Not implemented');
    }

    public function unarchive()
    {
        throw new Exception('Not implemented');
    }

    public function delete()
    {
        throw new Exception('Not implemented');
    }

    // SECTION: Methods relative to the reservation of cars
    public function reserve()
    {
        throw new Exception('Not implemented');
    }

    public function editReservation()
    {
        throw new Exception('Not implemented');
    }

    public function cancelReservation()
    {
        throw new Exception('Not implemented');
    }

    // SECTION: Methods relative to car maintenance
    public function newMaintenance()
    {
        throw new Exception('Not implemented');
    }

    public function editMaintenance()
    {
        throw new Exception('Not implemented');
    }

    public function deleteMaintenance()
    {
        throw new Exception('Not implemented');
    }

    // SECTION: Methods relative to database queries, table 'macchine'
    public function getCars()
    {
        throw new Exception('Not implemented');
    }

    public function getCarsBySede()
    {
        throw new Exception('Not implemented');
    }

    public function getCar()
    {
        throw new Exception('Not implemented');
    }

    // SECTION: Methods relative to database queries, table 'prenotazioni'
    public function getReservations()
    {
        throw new Exception('Not implemented');
    }

    public function getReservationsByUser()
    {
        throw new Exception('Not implemented');
    }

    // SECTION: Methods relative to database queries, table 'manutenzioni'
}