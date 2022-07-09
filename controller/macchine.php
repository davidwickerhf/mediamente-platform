<?php
require_once(INCLUDE_PATH . 'libraries/Controller.php');

class Macchine extends Controller
{
    public function __construct()
    {
        $this->carModel = $this->model('macchina');
    }

    public function index()
    {
        requireLogin(); // Richiedo login
        $var_in_view['pageTitle'] = 'Macchine';
        $var_in_view['macchine'] = array();

        // TODO Remove comment line below when refactoring to class structure
        // $this->view('macchine/index'); | Class constructor, incompatible with function constructors in place.

        $view = ROOT_PATH . 'views/macchine/index.php';
        require_once ROOT_PATH . 'views/inc/default.php';
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

    public function nuovaPrenotazione()
    {
        throw new Exception('Not implemented');
    }
}
