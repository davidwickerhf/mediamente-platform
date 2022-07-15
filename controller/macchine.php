<?php
require_once(INCLUDE_PATH . 'libraries/Controller.php');

/**
 * Controller for the page group `Macchine`
 *
 * @author  David Henry Francis Wicker @ Mediamente Consulting
 * @license MIT
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
    const INDEX_LOAD_DATA = 'loadIndexData';
    const INDEX_UPDATE_PRENOTAZIONI = 'indexUpdatePrenotazioni';
    const INDEX_UPDATE_STATISTICHE = 'indexUpdateStatistiche';
    // Macchine Page
    // Statistiche Page
    // Prenotazioni Page

    public function __construct()
    {
        $this->macchina = $this->model('macchina');
    }

    public function index()
    {
        requireLogin(); // Richiedo login

        // Set View Variables (Retrieve from SESSION)
        $data['pageTitle'] = 'Macchine';
        $data['prenotazioniState'] = 'prossime';
        $data['statisticheState'] = 'mensilmente';
        $data['sedeState'] = 'torino';
        $data['consulenteState'] = 'tutti';
        $data['calendarioMese'] = 'luglio';
        $data['calendarioState'] = 'calendario';

        // Check incoming ajax  & verify csrfToken
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            if ($_POST['action'] != "login" && $_POST['csrfToken'] != $_SESSION['csrfToken-' . $_POST['action'] . $_POST['csrfTokenID']]) {
                exitWithError("U02", "CSRF Attack - Sessione scaduta, aggiorna la pagina");
            }

            /** PROCESS AJAX REQUESTS
             * @todo Note, if the variable $data is changed 
             * (aka $vars_in_view), such changes will be applied only
             *  when the page is refreshed.
             *  Change data in $data in order to persist changes.
             *  In order to actualize changes directly, return the values
             *  through the POST response.
             */
            switch ($_POST['action']) {
                case Macchine::INDEX_UPDATE_PRENOTAZIONI:
                    // PRENOTAZIONI DROPDOWN BUTTON PRESSED
                    $state = $_POST['state'];
                    $tempdata = $_POST['data'];
                    if ($state == "prossime") {
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

                case Macchine::INDEX_UPDATE_STATISTICHE:
                    // PRENOTAZIONI DROPDOWN BUTTON PRESSED
                    $state = $_POST['state'];
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
        }

        // Create View
        $this->view('macchine/index', $data);
    }

    public function macchine()
    {
        throw new Exception('Not implemented');
    }

    public function statistiche()
    {
        throw new Exception('Not implemented');
    }

    public function prenotazioni()
    {
        throw new Exception('Not implemented');
    }

    public function prenota()
    {
        throw new Exception('Not implemented');
    }
}