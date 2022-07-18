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

require_once ROOT_PATH . 'classes/macchina.class.php';
require_once ROOT_PATH . 'classes/prenotazione.class.php';
require_once ROOT_PATH . 'classes/manutenzione.class.php';
require_once ROOT_PATH . 'libraries/Database.php';


/**
 * Model class for easy access to the database tables
 *  `macchine`, `prenotazioni` and `manutenzioni`.
 * PHP Version 7.4.
 *
 * @author    David Henry Francis Wicker (https://github.com/davidwickerhf) <davidwickerhf@gmail.com>
 * @license   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */
class Macchina
{
    private Database $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    // SECTION: Methods relative to the management of cars
    public function register(): CMacchina
    {
        throw new Exception('Not implemented');
    }

    public function archive(): CMacchina
    {
        throw new Exception('Not implemented');
    }

    public function unarchive(): CMacchina
    {
        throw new Exception('Not implemented');
    }

    public function delete(): bool
    {
        throw new Exception('Not implemented');
    }

    // SECTION: Methods relative to the reservation of cars
    public function reserve(): CPrenotazione
    {
        throw new Exception('Not implemented');
    }

    public function editReservation(): CPrenotazione
    {
        throw new Exception('Not implemented');
    }

    public function cancelReservation(): bool
    {
        throw new Exception('Not implemented');
    }

    // SECTION: Methods relative to car maintenance
    public function newMaintenance(): CManutenzione
    {
        throw new Exception('Not implemented');
    }

    public function editMaintenance(): CManutenzione
    {
        throw new Exception('Not implemented');
    }

    public function deleteMaintenance(): bool
    {
        throw new Exception('Not implemented');
    }

    // SECTION: Methods relative to database queries, table 'macchine'
    public function getCars(int $count): array
    {
        throw new Exception('Not implemented');
    }

    public function getCarsBySede(string $sede): array
    {
        throw new Exception('Not implemented');
    }

    public function getCar(string $id): CMacchina
    {
        throw new Exception('Not implemented');
    }

    // SECTION: Methods relative to database queries, table 'prenotazioni'
    public function getReservations(int $count): array
    {
        throw new Exception('Not implemented');
    }

    /**
     * Render Banner html and javascript
     * 
     * @param string username state of the prenotazioni dropdown.
     * @param ?int count Number of entries to retrieve. 
     *  Defaults to `0`, which will retrieve all entries.
     * @return array array containing `Prenotazione` objects
     * @see /classes/prenotazione.class.php
     */
    public function getReservationsByUser(string $username, ?int $count = 0): array
    {
        // Prepare statement
        $stmt = 'SELECT * 
            FROM prenotazioni
            WHERE username = :username 
            ORDER BY prenotazioni.created_at DESC';
        if ($count > 0) {
            $stmt = $stmt
                . ' LIMIT :count';
        }
        $this->db->query($stmt);
        $this->db->bind(':username', $username);
        if ($count > 0) {
            $this->db->bind(':count', $count);
        };
        // Get results
        $results = $this->db->resultSet();
        // Map results to array of Prenotazioni objects
        $prenotazioni = array();
        foreach ($results as $temp) {
            array_push($prenotazioni, new CPrenotazione($temp));
        }
        return $prenotazioni;
    }

    public function getOngoingReservetionByUser(string $username): CPrenotazione
    {
        //$this->db->query();
        //$this->db->
        return new CPrenotazione();
    }


    // SECTION: Methods relative to database queries, table 'manutenzioni'
}