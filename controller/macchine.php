<?php
require_once(INCLUDE_PATH . 'libraries/Controller.php');

if (!function_exists('renderBannerReservation')) {
    require_once ROOT_PATH . 'src/components/bannerReservation.php';
}

if (!function_exists('renderBannerGraph')) {
    require_once ROOT_PATH . 'src/components/bannerGraph.php';
}

if (!function_exists('renderCalendar')) {
    require_once ROOT_PATH . 'src/components/calendar.php';
}


/**
 * Controller for Macchine pages
 * PHP Version 7.4.
 * 
 * Class wide variables should only define function names.
 * State variables should be defined at the start of each controller action.
 *  These merely define the state of the layout, NOT the content.
 * Content loaded from database is NOT passed through the $data variable, 
 *  but loaded through ajax requests. These requests may include in 
 *  their response HTML to inject through jquery.
 * 
 * POST Request Schema:
 *  data: {action: '', state: '', args: {}}
 * 
 * POST Request Response Schema:
 *  data: {contents: { html: '' }, vars: {}}
 * 
 * GET Request Schema:
 *  data: { action: ''}
 * 
 * GET Request Response Schema:
 *  data: { contents: { html: '' }, vars: {}}
 *
 * @author    David Henry Francis Wicker (https://github.com/davidwickerhf) <davidwickerhf@gmail.com>
 * @license   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */
class Macchine extends Controller
{

    // STATE OPTIONS
    const SEDE_STATES = array(
        'torino' => 'Torino',
        'milano' => 'Milano',
        'empoli' => 'Empoli',
        'bologna' => 'Bologna',
        'tuttelesedi' => 'Tutte le sedi'
    );
    // Index Page
    const INDEX_PRENOTAZIONI_STATES = array('prossima' => 'Prossima', 'incorso' => 'In corso');
    const INDEX_STATISTICHE_STATES = array('mensilmente' => 'Mensilmente', 'annualmente' => 'Annualmente');
    const INDEX_DISPONIBILITA_STATES = array('oggi' => 'Oggi', 'domani' => 'Domani', 'dopodomani' => 'Dopodomani');

    // Macchine Page
    // Statistiche Page
    // Prenotazioni page

    // CONTROLLERS
    const INDEX = 'index';
    const MACCHINE = 'macchine';
    const STATISTICHE = 'statistiche';
    const PRENOTAZIONI = 'prenotazioni';
    const PRENOTA = 'prenota';

    // METHODS for AJAX
    const RESERVE = 'reserve';
    // Index Page
    const INDEX_LOAD_DATA = 'indexLoadData';
    const INDEX_UPDATE_SEDE = 'indexUpdateSede';
    const INDEX_UPDATE_PRENOTAZIONI = 'indexUpdatePrenotazioni';
    const INDEX_UPDATE_STATISTICHE = 'indexUpdateStatistiche';
    const INDEX_UPDATE_DISPONIBILITA = 'indexUpdateDisponibilita';
    const INDEX_UPDATE_CALENDARIO = 'indexUpdateCalendario';

    // Macchine Page
    // Statistiche Page
    // Prenotazioni Page
    // Side Panel

    // GLOBAL PAGE VARIABLES


    // Database model
    private Macchina $macchineModel;
    private Log $logger;

    public function __construct()
    {
        $this->macchineModel = $this->model('macchina');
        $this->logger = new Log(array('controller' => 'macchine', 'action' => 'Controller Exception', $this), null, true);
    }

    // UTILITY FUNCTIONS
    /**
     * Load contents for Disponibilita component of the Banner
     *  in the index page.
     * 
     * @param array data Page state data.
     * @throws PDOException if binding values to parameters fails.
     */
    private function loadIndexDisponibilita(array $data): array
    {
        $state = $data['indexDisponibilitaState'];
        $contents = array();
        if ($state == "oggi") {
            // UPCOMING RESERVATIONS
            //Load Contents from Model
            $disponibili = $this->macchineModel->getAvailableCars(new DateTime(date('Y-m-d', time())));
            $prenotate = $this->macchineModel->getReservedCars(new DateTime(date('Y-m-d', time())));
        } elseif ($state == 'domani') {
            // ONGOING RESERVATIONS
            // Load contents from Model
            $disponibili = $this->macchineModel->getAvailableCars(new DateTime('tomorrow'));
            $prenotate = $this->macchineModel->getReservedCars(new DateTime('tomorrow'));
        } else {
            // ONGOING RESERVATIONS
            // Load contents from Model
            $disponibili = $this->macchineModel->getAvailableCars(new DateTime('tomorrow + 1day'));
            $prenotate = $this->macchineModel->getReservedCars(new DateTime('tomorrow + 1day'));
        }

        if (!($data['indexSedeState'] == 'tuttelesedi')) {
            $temp = array();
            foreach ($disponibili as $macchina) {
                if ($macchina->sede == $data['indexSedeState'])
                    array_push($temp, $macchina);
            }
            $disponibili = $temp;

            $temp = array();
            foreach ($prenotate as $macchina) {
                if ($macchina->sede == $data['indexSedeState'])
                    array_push($temp, $macchina);
            }
            $prenotate = $temp;
        }
        $contents['disponibili'] = count($disponibili);
        $contents['prenotate'] = count($prenotate);
        return $contents;
    }

    /**
     * Load contents for Prenotazioni component of the Banner
     *  in the index page.
     * 
     * @param array data Page state data.
     * @throws PDOException if binding values to parameters fails.
     */
    private function loadIndexPrenotazioni(array $data): array
    {
        $contents = array();
        $state = $data['indexPrenotazioniState'];
        $html = '';
        if ($state == 'incorso') {
            $reservation = $this->macchineModel->getUserOngoingReservation(getMyUsername());
            if (is_null($reservation)) {
                $html =  '<p>Nessuna prenotazione in corso</p>';
            } else {
                $car = $this->macchineModel->getCar($reservation->id_macchina);
                $html = renderBannerReservation($reservation, $car);
            }
        } else {
            $username = getMyUsername();
            $reservations = $this->macchineModel->getUserFutureReservations($username, 1);
            $temp = array();
            foreach ($reservations as $reservation) {
                if ($reservation->from_date > new DateTime('today'))
                    array_push($temp, $reservation);
            }
            if (empty($temp)) {
                $html =  '<p>Nessuna prenotazione futura</p>';
            } else {
                foreach ($temp as $prenotazione) {
                    $car = $this->macchineModel->getCar($reservation->id_macchina);
                    $html .= renderBannerReservation($prenotazione, $car);
                }
            }
        }
        $contents['html'] = '<div id="bannerPrenotazioni">' . $html . '</div>';
        return $contents;
    }

    /**
     * Load contents for Graph component of the Banner
     *  in the index page.
     * 
     * @param string data Page state data.
     * @return array contents
     * @throws PDOException if binding values to parameters fails.
     */
    private function loadIndexGraph(array $data): array
    {
        $state = $data['indexStatisticheState'];
        $tcontent = array();
        $rows = array();
        $columns = array();
        $today = new DateTime('today');

        // Load Columns
        $max = 0;
        foreach (range(6, 0) as $index) {
            $reservations = array();

            // Load reservations
            if ($state == 'mensilmente') {
                $date = new DateTime('today -' . $index .  ' month');
                $month = $date->format('n');
                $year = $date->format('Y');

                // Format Month
                $df = new IntlDateFormatter('it_IT', IntlDateFormatter::SHORT, IntlDateFormatter::NONE);
                $df->setPattern('MMM');
                $name = ucwords(strtolower($df->format($date)));

                $reservations = $this->macchineModel->getMonthReservation($month, $year);
                $name = ucwords($name);
            } else {
                $date = new DateTime('today -' . $index .  ' year');
                $name = $date->format('Y');
                $year = $date->format('Y');
                $reservations = $this->macchineModel->getYearReservation($year);
            }

            // Filter reservations by locations
            if ($data['indexSedeState'] != 'tuttelesedi') {
                $temp = array();
                foreach ($reservations as $reservation) {
                    $car = $this->macchineModel->getCar($reservation->id_macchina);
                    if ($car->sede == $data['indexSedeState'])
                        array_push($temp, $reservation);
                }
                $reservations = $temp;
            }

            $column = array(
                'value' => count($reservations),
                'name' => $name
            );
            array_push($columns, $column);

            if (count($reservations) > $max) {
                $max = count($reservations);
            }
        }

        // Load Rows
        $top = (ceil($max) % 5 === 0) ? ceil($max) : round(($max + 5 / 2) / 5) * 5;
        if ($top < 5) {
            $rows = array(1, 2, 3, 4, 5);
        } else {
            $step = $top / 5;
            $rows = array(
                $top,
                $top - $step,
                $top - 2 * $step,
                $top - 3 * $step,
                $top - 4 * $step
            );
        }

        $tcontent['rows'] = $rows;
        $tcontent['columns'] = $columns;
        $contents['html'] = renderBannerGraph($tcontent);
        return $contents;
    }

    /**
     * Load contents for Calendar component of index page.
     * 
     * @param string data Page state data.
     * @return array contents
     * @throws PDOException if binding values to parameters fails.
     */
    private function loadIndexCalendar(array $data): array
    {
        $state = $data['indexCalendarioMese'];
        $content = array();

        // calculate start date

        // loop trough dates and save reservations
        $tcontent = array();
        foreach (range(1, 42) as $index) {
        }

        // render html
        $content['html'] = renderCalendar($tcontent, $data);
        return $content;
    }

    public function index()
    {
        requireLogin(); // Richiedo login

        // VIEW VARIABLES (Retrieve from SESSION)
        $data['pageTitle'] = 'Macchine';

        // Global Page Variables
        $data['indexSedeState'] = (isset($_SESSION['indexSedeState']) && !empty($_SESSION['indexSedeState'])) ? $_SESSION['indexSedeState'] : 'tuttelesedi';


        // Banner variables
        $data['indexPrenotazioniState'] = (isset($_SESSION['indexPrenotazioniState']) && !empty($_SESSION['indexPrenotazioniState'])) ? $_SESSION['indexPrenotazioniState'] : 'prossima';

        $data['indexStatisticheState'] = (isset($_SESSION['indexStatisticheState']) && !empty($_SESSION['indexStatisticheState'])) ? $_SESSION['indexStatisticheState'] : 'mensilmente';

        $data['indexDisponibilitaState'] = (isset($_SESSION['indexDisponibilitaState']) && !empty($_SESSION['indexDisponibilitaState'])) ? $_SESSION['indexDisponibilitaState'] : 'oggi';

        // Calendario
        $data['indexConsulenteState'] = (isset($_SESSION['indexConsulenteState']) && !empty($_SESSION['indexConsulenteState'])) ? $_SESSION['indexConsulenteState'] : 'tutti';

        $data['indexCalendarioMese'] = (isset($_SESSION['indexCalendarioMese']) && !empty($_SESSION['indexCalendarioMese'])) ? $_SESSION['indexCalendarioMese'] : strtolower(date('Y-m-d', time()));

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
                $contents = array();
                switch ($_POST['action']) {
                    case Macchine::INDEX_UPDATE_SEDE:
                        // SEDE DROPDOWN BUTTON PRESSED
                        // Persist staste change
                        $state = $_POST['state'];
                        $_SESSION['indexSedeState'] = $state;
                        $data['indexSedeState'] = $state;
                        break;
                    case Macchine::INDEX_UPDATE_PRENOTAZIONI:
                        // PRENOTAZIONI DROPDOWN BUTTON PRESSED
                        // Persist staste change
                        $state = $_POST['state'];
                        $_SESSION['indexPrenotazioniState'] = $state;
                        $data['indexPrenotazioniState'] = $state;

                        $contents = $this->loadIndexPrenotazioni($data);
                        break;

                    case Macchine::INDEX_UPDATE_STATISTICHE:
                        // PRENOTAZIONI DROPDOWN BUTTON PRESSED
                        $state = $_POST['state'];
                        $_SESSION['indexStatisticheState'] = $state;
                        $data['indexStatisticheState'] = $state;

                        $contents = $this->loadIndexGraph($data);
                        break;

                    case Macchine::INDEX_UPDATE_DISPONIBILITA:
                        // PRENOTAZIONI DROPDOWN BUTTON PRESSED
                        $state = $_POST['state'];

                        // Persist data changes
                        $_SESSION['indexDisponibilitaState'] = $state;
                        $data['indexDisponibilitaState'] = $state;

                        $contents = $this->loadIndexDisponibilita($data);
                        break;

                    case Macchine::INDEX_UPDATE_CALENDARIO:
                        // PRENOTAZIONI DROPDOWN BUTTON PRESSED
                        $state = $_POST['state'];
                        $previous = $data['indexCalendarioMese'];
                        if ($state == 'left') {
                            $newdate = new DateTime(substr($previous, 0, -3) . '-01 -1 month');
                        } else {
                            $newdate = new DateTime(substr($previous, 0, -3) . '-01 +1 month');
                        }
                        $new = $newdate->format('Y-m-d');
                        $_SESSION['indexCalendarioMese'] = $new;
                        $data['indexCalendarioMese'] = $new;

                        $contents = $this->loadIndexCalendar($data);
                        break;

                    default:
                        # code...
                        break;
                }

                // Return response
                die(json_encode(array('contents' => $contents, 'vars' => $data)));
            } else if ($_SERVER['REQUEST_METHOD'] === 'GET') {

                if ($_GET['action'] != "login" && $_GET['csrfToken'] != $_SESSION['csrfToken-' . $_GET['action'] . $_GET['csrfTokenID']]) {
                    exitWithError("U02", "CSRF Attack - Sessione scaduta, aggiorna la pagina");
                }

                $contents = array();
                switch ($_GET['action']) {
                    case Macchine::INDEX_LOAD_DATA:
                        // LOAD USER PRENOTAZIONI
                        $temp = $this->loadIndexPrenotazioni($data);
                        $contents[Macchine::INDEX_UPDATE_PRENOTAZIONI] = $temp;

                        // LOAD STATS
                        $temp = $this->loadIndexGraph($data);
                        $contents[Macchine::INDEX_UPDATE_STATISTICHE] = $temp;

                        // LOAD CAR AVAILABILITY
                        $temp = $this->loadIndexDisponibilita($data);
                        $contents[Macchine::INDEX_UPDATE_DISPONIBILITA] = $temp;

                        // TODO LOAD CALENDAR       
                        $temp = $this->loadIndexCalendar($data);
                        $contents[Macchine::INDEX_UPDATE_CALENDARIO] = $temp;
                        break;
                    default:
                        # code...
                        break;
                }
                die(json_encode(array('contents' => $contents, 'vars' => $data)));
            } else {
                // What
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

        // Global Page Variables
        $data['indexSedeState'] = (isset($_SESSION['indexSedeState']) && !empty($_SESSION['indexSedeState'])) ? $_SESSION['indexSedeState'] : 'tuttelesedi';

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
        requireLogin();
        // VIEW VARIABLES (Retrieve from SESSION)
        $data['pageTitle'] = 'Macchine';

        // Global Page Variables
        $data['indexSedeState'] = (isset($_SESSION['indexSedeState']) && !empty($_SESSION['indexSedeState'])) ? $_SESSION['indexSedeState'] : 'tuttelesedi';

        // Create View
        $this->view('macchine/prenotazioni', $data);
    }

    public function statistiche()
    {
        requireLogin();
        // VIEW VARIABLES (Retrieve from SESSION)
        $data['pageTitle'] = 'Macchine';

        // Global Page Variables
        $data['indexSedeState'] = (isset($_SESSION['indexSedeState']) && !empty($_SESSION['indexSedeState'])) ? $_SESSION['indexSedeState'] : 'tuttelesedi';

        // Create View
        $this->view('macchine/statistiche', $data);
    }
}