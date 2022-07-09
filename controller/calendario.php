<?php
function index($params)
{
    requireLogin(); // Richiedo login


    global $db; // uso il database
    // Inizio corpo della funzione //////

    require_once CLASS_PATH . 'calendario.class.php';


    $calendario = new Calendario();

    if (!count($calendario->getElencoCalendariEsterni()))
        $var_in_view['getStarted'] = 1;

    if (!$calendario->getCalendarioUtente(getMyUsername())) {
        $calendario->aggiungiCalendario();
        $values =            array(
            "nome" => addslashes(getCognomeNome(getMyUsername())),
            "colore" => '#cccccc',
            "tipo" => 'personale',
            "username" => getMyUsername()
        );

        if (!$calendario->salvaCalendario(
            $values
        ))
            $calendario->rimuoviCalendario();
    }

    $var_in_view['calendari'] = $calendario->getElencoCalendari(" AND tipo='generico'");
    $var_in_view['calendari_personali'] = $calendario->getElencoCalendari(" AND tipo='personale'");

    $var_in_view['pageTitle'] = "Calendari";

    $var_in_view['printDebugQuery'] =  $db->captured_errors;
    // GENERO VIEW
    $view = ROOT_PATH . "views/calendario/index.php"; // includo la view
    $layout = ROOT_PATH . "views/inc/default.php"; // includo il layout

    generate_view($view, $layout, $var_in_view);
}


function visualizza($params)
{
    requireLogin(); // Richiedo login


    global $db; // uso il database
    // Inizio corpo della funzione //////

    require_once CLASS_PATH . 'calendario.class.php';
    if (!isset($params[0]) || !$params[0])
        throw_404();

    $id = filter_var($params[0], FILTER_SANITIZE_NUMBER_INT);


    if (isset($id) && is_numeric($id)) {
        $var_in_view['id'] = $id;
        $calendario = new Calendario(array(
            "id" => $id
        ));

        require_once CLASS_PATH . 'progetto.class.php';
        $progetto = new Progetto();

        if ($calendario->getTipoCalendario() == "generico")
            $var_in_view['progetti'] = $progetto->getElencoProgettiCalendario($id);
        elseif ($calendario->getTipoCalendario() == "personale")
            $var_in_view['progetti'] = $progetto->getElencoProgettiUtente($calendario->getUsername());


        $filtro = 'AND codice_cliente IN(';
        foreach ($var_in_view['progetti'] as $p)
            $filtro .= "'" . $p->codice_cliente . "',";

        $filtro = rtrim($filtro, ",") . ")";

        require_once CLASS_PATH . 'cliente.class.php';
        $cliente = new Cliente();
        $var_in_view['clienti'] = $cliente->getElencoClienti($filtro);


        $var_in_view['readonly'] = false;
    }
    /*else
    {
        $var_in_view['username']=$filtro['username'];
        $calendario=new Calendario(Array(
            "username"=>$filtro['username']
            ));
        $var_in_view['readonly']=true;
    }*/

    $var_in_view['calendario'] = $calendario->getCalendario();

    $var_in_view['calendari'] = $calendario->getElencoCalendari(" AND tipo='generico'");




    $var_in_view['pageTitle'] = "Calendario " . $calendario->getTitle();

    $var_in_view['printDebugQuery'] =  $db->captured_errors;
    // GENERO VIEW
    $view = ROOT_PATH . "views/calendario/visualizza.php"; // includo la view
    $layout = ROOT_PATH . "views/inc/default.php"; // includo il layout

    generate_view($view, $layout, $var_in_view);
}

function visualizzaUtente($params)
{
    requireLogin(); // Richiedo login


    global $db; // uso il database
    // Inizio corpo della funzione //////

    require_once CLASS_PATH . 'calendario.class.php';
    if (!isset($params[0]) || !$params[0])
        throw_404();

    $username = filter_var($params[0], FILTER_SANITIZE_STRING);

    $calendario = new Calendario();

    $cal = $calendario->getCalendarioUtente($username);

    if (!$cal) {
        if ($db->get_var("SELECT COUNT(*) FROM utenti WHERE username='$username' AND enabled=1")) {
            $calendario->aggiungiCalendario();
            $values =            array(
                "nome" => addslashes(getCognomeNome($username)),
                "colore" => '#cccccc',
                "tipo" => 'personale',
                "username" => $username
            );

            if (!$calendario->salvaCalendario(
                $values
            ))
                $calendario->rimuoviCalendario();
            $cal = $calendario->getCalendarioUtente($username);
        } else
            throw_404();
    }
    header("Location:" . SERV_URL . "calendario/visualizza/" . $cal->id);
    die();
}

function visualizzaProgetto($params)
{
    requireLogin(); // Richiedo login


    global $db; // uso il database
    // Inizio corpo della funzione //////

    require_once CLASS_PATH . 'calendario.class.php';
    if (!isset($params[0]) || !$params[0])
        throw_404();

    $id_progetto = filter_var($params[0], FILTER_SANITIZE_NUMBER_INT);

    $calendario = new Calendario();

    $cal = $calendario->getCalendarioProgetto($id_progetto);

    if (!$cal)
        throw_404();
    header("Location:" . SERV_URL . "calendario/visualizza/" . $cal->id);
    die();
}


function aggiungi($params)
{
    requireLogin("calendario", "aggiungi"); // Richiedo login


    global $db; // uso il database
    // Inizio corpo della funzione //////

    require_once CLASS_PATH . 'calendario.class.php';


    if (isset($params[0]) && is_numeric($params[0])) {
        $calendario = new Calendario(array(
            "id" => $params[0]
        ));
        if (!$calendario->exists())
            throw_404();
        $var_in_view['calendario'] = $calendario->getCalendario();
        $var_in_view['pageTitle'] = "Modifica calendario " . $calendario->getNome();
    } else {
        $calendario = new Calendario();
        $var_in_view['calendario'] = null;
        $var_in_view['pageTitle'] = "Aggiungi calendario";
    }


    $var_in_view['printDebugQuery'] =  $db->captured_errors;
    // GENERO VIEW
    $view = ROOT_PATH . "views/calendario/aggiungi.php"; // includo la view
    $layout = ROOT_PATH . "views/inc/default.php"; // includo il layout

    generate_view($view, $layout, $var_in_view);
}

function importExport()
{
    requireLogin(); // Richiedo login


    global $db; // uso il database
    // Inizio corpo della funzione //////

    require_once CLASS_PATH . 'calendario.class.php';
    $calendario = new Calendario();

    $var_in_view['calendari'] = $calendario->getElencoCalendariEsterni();
    $var_in_view['pageTitle'] = "Calendari esterni";

    $var_in_view['tokenEsportazione'] = $calendario->generaTokenEsportazione(getMyUsername());

    $var_in_view['printDebugQuery'] =  $db->captured_errors;
    // GENERO VIEW
    $view = ROOT_PATH . "views/calendario/importExport.php"; // includo la view
    $layout = ROOT_PATH . "views/inc/default.php"; // includo il layout

    generate_view($view, $layout, $var_in_view);
}

function aggiungiCalendarioEsterno($params)
{
    requireLogin(); // Richiedo login


    global $db; // uso il database
    // Inizio corpo della funzione //////

    require_once CLASS_PATH . 'calendario.class.php';


    if (isset($params[0]) && is_numeric($params[0])) {
        $calendario = new Calendario();
        $var_in_view['calendario'] = $calendario->getCalendarioEsterno($params[0]);
        $var_in_view['pageTitle'] = "Modifica calendario esterno";
    } else {
        $calendario = new Calendario();
        $var_in_view['calendario'] = null;
        $var_in_view['pageTitle'] = "Aggiungi calendario esterno";
    }



    $var_in_view['printDebugQuery'] =  $db->captured_errors;
    // GENERO VIEW
    $view = ROOT_PATH . "views/calendario/aggiungiCalendarioEsterno.php"; // includo la view
    $layout = ROOT_PATH . "views/inc/default.php"; // includo il layout

    generate_view($view, $layout, $var_in_view);
}

function ICS($params)
{

    global $db; // uso il database
    // Inizio corpo della funzione //////

    require_once CLASS_PATH . 'calendario.class.php';


    $calendario = new Calendario();

    $username = preg_replace("/[^A-Za-z0-9]/", '', $params[0]);
    $token = preg_replace("/[^A-Za-z0-9]/", '', $params[1]);
    $interni = preg_replace("/[^A-Za-z0-9]/", '', $params[2]);

    require_once CLASS_PATH . 'utente.class.php';
    $utente = new Utente(array("username" => $username));

    if (!$utente->isEnabled())
        throw_403();

    if ($calendario->generaTokenEsportazione($username) != $token)
        throw_403();


    header('Content-Type: text/calendar; charset=utf-8');
    header('Content-Disposition: attachment; filename=calendario_' . $username . '.ics');

    echo $calendario->calendarioICS($username, $interni);

    die();
}

function esporta($params)
{
    requireLogin(); // Richiedo login

    global $db; // uso il database
    // Inizio corpo della funzione //////

    $var_in_view['pageTitle'] = "Esporta calendari";

    require_once CLASS_PATH . 'calendario.class.php';
    $calendario = new Calendario();

    $var_in_view['calendari'] = $calendario->getElencoCalendari(" AND tipo='generico'");
    $var_in_view['calendari_personali'] = $calendario->getElencoCalendari(" AND tipo='personale'");
    var_dump($_SESSION["POST"]);
    if (isset($_SESSION["POST"])) {
        $calendari = $_SESSION["POST"]["codice_calendario"];
        $utenti = $_SESSION["POST"]["codice_utente"];
        $dataInizio = $_SESSION["POST"]["data_inizio"];
        $dataFine = $_SESSION["POST"]["data_fine"];
    }

    if (isset($dataInizio) && isset($dataFine)) {
        $res = $calendario->esportaCalendari($calendari, $utenti, $dataInizio, $dataFine);

        if ($res) {
            header("Content-type: text/csv;charset=utf-8");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Transfer-Encoding: binary");
            header("Content-Disposition: attachment; filename*=UTF-8''exportCalendar.csv");
            $output = fopen('php://output', 'w');
            ob_clean();
            fputs($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
            $data1 = new DateTime($dataInizio);
            $data2 = new DateTime($dataFine);
            $interval = (int)date_diff($data2, $data1)->format('%a'); //$data1->diff($data2);

            $calendar = array();

            $datafor = $data1;
            $dateString = $datafor->format('Y-m-d');
            for ($i = 0; $i < $interval + 1; $i++) {
                $calendar[$dateString] = array();
                $calendar[$dateString][0] = array();
                #$calendar[$dateString][1] = array();
                $dateString = ($datafor->add(new DateInterval('P1D')))->format('Y-m-d');
            }


            $header = array();
            $header2 = array();
            $header[] = 'Data';
            $header2[] = '';

            $rowsData = array();
            $rowsData[] = 'Data';

            $rowsUsers = array();

            foreach ($res as $row) {
                if (!in_array($row->username, $header)) {
                    $header[] = $row->username;
                    $header[] = $row->username;
                    $header[] = $row->username;
                    $header2[] = 'ore';
                    $header2[] = 'fascia oraria';
                    $header2[] = 'progetto';

                    $rowsUsers[] = $row->username;
                }
                $inizio = DateTime::createFromFormat('Y-m-d H:i:s', $row->inizio);
                $fine = DateTime::createFromFormat('Y-m-d H:i:s', $row->fine);

                #$half = (date('H', $inizio)<=13 ? 0 : 1);
                $ore = (($fine->format('H') + 0) - ($inizio->format('H') + 0) > 8 ? 0 : ($fine->format('H') + 0) - ($inizio->format('H') + 0));
                $fascia_oraria = strval($inizio->format('H:i:s')) . " - " . strval($fine->format('H:i:s'));
                $dateDiff = $inizio->diff($fine);
                $intervalDays = (int)date_diff($fine, $inizio)->format('%a');

                $datafor = $inizio;
                $dateString = $datafor->format('Y-m-d');
                for ($i = 0; $i < $intervalDays + 1; $i++) {
                    $idx_day = count($calendar[$dateString][$row->username]);
                    if (array_key_exists($dateString, $calendar)) {
                        $calendar[$dateString][$idx_day][$row->username]['ore'] = $ore;
                        $calendar[$dateString][$idx_day][$row->username]['fascia oraria'] = $fascia_oraria;
                        $calendar[$dateString][$idx_day][$row->username]['progetto'] = $row->nome . " / " . $row->titolo;
                    }
                    $dateString = ($datafor->add(new DateInterval('P1D')))->format('Y-m-d');
                }
            }
            switch ($i) {
                case 0:
                    fputcsv($output, $header, ";");
                    fputcsv($output, $header2, ";");

                    #print_r($header);
                    #print_r($calendar);

                    foreach ($calendar as $key1 => $value1) {
                        foreach ($value1 as $key2 => $value2) {
                            $rowCsv = array();
                            $rowCsv[] = $key1;
                            for ($y = 1; $y < count($header); $y = $y + 3) {
                                if (array_key_exists($header[$y], $value2)) {
                                    $rowCsv[] = ($value2[$header[$y]]['ore'] == 0 ? '8' : strval($value2[$header[$y]]['ore']));
                                    $rowCsv[] = $value2[$header[$y]]['fascia oraria'];
                                    $rowCsv[] = $value2[$header[$y]]['progetto'];
                                } else {
                                    $rowCsv[] = "";
                                    $rowCsv[] = "";
                                    $rowCsv[] = "";
                                }
                            }
                            fputs($output, implode($rowCsv, ';') . "\r\n");
                            //fputcsv($output, $rowCsv, ";");
                        }
                    }
                    ob_flush();
                    fclose($output);
                    break;
                case 1:
                    $rowsCSV = array();
                    foreach ($res as $row) {
                        if (!array_key_exists($row->username, $rowsCSV)) {
                            $rowsCSV[$row->username] = array();
                            $rowsCSV[$row->username][] = $row->username;
                        }
                    }

                    //print_r($rowsUsers);

                    foreach ($calendar as $key1 => $value1) {
                        foreach ($value1 as $key2 => $value2) {
                            $rowsData[] = $key1;

                            for ($y = 0; $y < count($rowsUsers); $y++) {
                                if (array_key_exists($rowsUsers[$y], $value2)) {
                                    $rowsCSV[$rowsUsers[$y]][] = ($value2[$rowsUsers[$y]]['ore'] == 0 ? '8' : strval($value2[$rowsUsers[$y]]['ore'])) . ' ( ' . $value2[$rowsUsers[$y]]['fascia oraria'] . ' ) - ' . $value2[$rowsUsers[$y]]['progetto'];
                                } else {
                                    $rowsCSV[$rowsUsers[$y]][] = "";
                                }
                            }
                        }
                    }

                    fputcsv($output, $rowsData, ";");
                    foreach ($rowsCSV as $key1 => $value1) {
                        fputs($output, implode($value1, ';') . "\r\n");
                    }
                    ob_flush();
                    fclose($output);
                    break;
            }
            unset($_SESSION['POST']);
            die();
        }
    }

    $view = ROOT_PATH . "views/calendario/esporta.php"; // includo la view
    $layout = ROOT_PATH . "views/inc/default.php"; // includo il layout

    generate_view($view, $layout, $var_in_view);
}