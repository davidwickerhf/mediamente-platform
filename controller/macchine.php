<?php
require_once(INCLUDE_PATH . 'libraries/Controller.php');

/**
 * Controller for Macchine pages
 * PHP Version 7.4.
 *
 * @author    David Henry Francis Wicker (https://github.com/davidwickerhf) <davidwickerhf@gmail.com>
 * @license   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */
class Macchine extends Controller
{

    // CONTROLLERS
    const INDEX = 'index';
    const MACCHINE = 'macchine';
    const STATISTICHE = 'statistiche';
    const PRENOTAZIONI = 'prenotazioni';
    const PRENOTA = 'prenota';

    // METHODS for AJAX
    // Index Page
    const INDEX_LOAD_DATA = 'indexLoadData';
    const INDEX_UPDATE_SEDE = 'indexUpdateSede';
    const INDEX_UPDATE_PRENOTAZIONI = 'indexUpdatePrenotazioni';
    const INDEX_UPDATE_STATISTICHE = 'indexUpdateStatistiche';
    // Macchine Page
    // Statistiche Page
    // Prenotazioni Page
    // Side Panel 


    // Database model
    private Macchina $macchineModel;

    public function __construct()
    {
        $this->macchineModel = $this->model('macchina');
    }

    public function index()
    {
        requireLogin(); // Richiedo login

        // VIEW VARIABLES (Retrieve from SESSION)
        $data['pageTitle'] = 'Macchine';

        // Banner variables
        $data['indexPrenotazioniState'] = (isset($_SESSION['indexPrenotazioniState']) && !empty($_SESSION['indexPrenotazioniState'])) ? $_SESSION['indexPrenotazioniState'] : 'prossime';

        $data['indexStatisticheState'] = (isset($_SESSION['indexStatisticheState']) && !empty($_SESSION['indexStatisticheState'])) ? $_SESSION['indexStatisticheState'] : 'mensilmente';

        // Global Page Variables
        $data['indexSedeState'] = (isset($_SESSION['indexSedeState']) && !empty($_SESSION['indexSedeState'])) ? $_SESSION['indexSedeState'] : 'tuttelesedi';

        // Calendario
        $data['indexConsulenteState'] = (isset($_SESSION['indexConsulenteState']) && !empty($_SESSION['indexConsulenteState'])) ? $_SESSION['indexConsulenteState'] : 'tutti';

        $data['indexCalendarioMese'] = (isset($_SESSION['indexCalendarioMese']) && !empty($_SESSION['indexCalendarioMese'])) ? $_SESSION['indexCalendarioMese'] : strtolower(date('F', time()));

        $data['indexCalendarioState'] = (isset($_SESSION['indexCalendarioState']) && !empty($_SESSION['indexCalendarioState'])) ? $_SESSION['indexCalendarioState'] : 'calendario';

        // Check incoming ajax  & verify csrfToken
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if ($_POST['action'] != "login" && $_POST['csrfToken'] != $_SESSION['csrfToken-' . $_POST['action'] . $_POST['csrfTokenID']]) {
                    exitWithError("U02", "CSRF Attack - Sessione scaduta, aggiorna la pagina");
                }

                /** PROCESS AJAX POST REQUESTS
                 * @todo Note, if the variable $data is changed 
                 * (aka $vars_in_view), such changes will be applied only
                 *  when the page is refreshed.
                 *  Change data in $data in order to persist changes.
                 *  In order to actualize changes directly, return the values
                 *  through the POST response.
                 */
                switch ($_POST['action']) {
                    case Macchine::INDEX_UPDATE_SEDE:
                        // SEDE DROPDOWN BUTTON PRESSED
                        // Persist staste change
                        $state = $_POST['state'];
                        $_SESSION['indexSedeState'] = $state;

                        $tempdata = $_POST['data'];
                        // Load Data


                        die(json_encode(array('state' => $state, 'data' => $tempdata)));
                        break;
                    case Macchine::INDEX_UPDATE_PRENOTAZIONI:
                        // PRENOTAZIONI DROPDOWN BUTTON PRESSED
                        // Persist staste change
                        $state = $_POST['state'];
                        $_SESSION['indexPrenotazioniState'] = $state;

                        $tempdata = $_POST['data'];
                        if ($state == "prossime") {
                            // UPCOMING RESERVATIONS
                            //Load Contents from Model
                            $reservations = $this->macchineModel->getUserReservations(getMyUsername(), 2);
                        } else {
                            // ONGOING RESERVATIONS
                            $reservations = $this->macchineModel->getUserOngoingReservations(getMyUsername(), 2);
                        }
                        // Prepare data

                        $contents = array();
                        foreach ($reservations as $reservation) {
                            array_push($contents, $reservation->toArray());
                        }
                        $tempdata['contents'] = $contents;
                        die(json_encode(array('state' => $state, 'data' => $tempdata)));
                        break;

                    case Macchine::INDEX_UPDATE_STATISTICHE:
                        // PRENOTAZIONI DROPDOWN BUTTON PRESSED
                        $state = $_POST['state'];
                        $_SESSION['indexStatisticheState'] = $state;
                        $tempdata = $_POST['data'];
                        if ($state == "mensilmente") {
                            // UPCOMING RESERVATIONS
                            //Load Contents from Model
                            $tempdata['contents'] = array();
                            //$this->macchina->getOngoingReservationsByUser();

                            // Persist data changes

                            // Return data
                            die(json_encode(array('state' => $state, 'data' => $tempdata)));
                        } else {
                            // ONGOING RESERVATIONS
                            // Load contents from Model
                            $tempdata['contents'] = array();

                            // Persist data changes

                            // Return data
                            die(json_encode(array('state' => $state, 'data' => $tempdata)));
                        }
                        break;

                    default:
                        # code...
                        break;
                }

                // Return response
                die(json_encode(array()));
            } else if ($_SERVER['REQUEST_METHOD'] === 'GET') {

                switch ($_GET['action']) {
                    case Macchine::INDEX_LOAD_DATA:
                        // LOAD USER PRENOTAZIONI


                        // LOAD STATES

                        // LOAD CAR AVAILABILITY

                        // LOAD PRENOTAZIONI 
                        break;

                    default:
                        # code...
                        break;
                }
            }
        }

        // Create View
        $this->view('macchine/index', $data);
    }

    public function macchine()
    {
        requireLogin();
        // VIEW VARIABLES (Retrieve from SESSION)
        $data['pageTitle'] = 'Macchine';
        // variables

        // Check incoming ajax
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if ($_POST['action'] != "login" && $_POST['csrfToken'] != $_SESSION['csrfToken-' . $_POST['action'] . $_POST['csrfTokenID']]) {
                    exitWithError("U02", "CSRF Attack - Sessione scaduta, aggiorna la pagina");
                }

                switch ($_POST['action']) {
                }
            } else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            }
        }

        // Create View
        $this->view('macchine/macchine', $data);
    }

    public function prenotazioni()
    {
        throw new Exception('Not implemented');
    }

    public function statistiche()
    {
        throw new Exception('Not implemented');
    }

    // CONTROLLER WIDE REQUESTS
    public function panel()
    {
        throw new Exception('Not implemented');
    }
}