<?php

/**
 * Class mirrows table `macchine` in the database.
 * PHP Version 7.4.
 *
 * @author    David Henry Francis Wicker (https://github.com/davidwickerhf) <davidwickerhf@gmail.com>
 * @license   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */

require_once ROOT_PATH  . 'classes/log.class.php';

class CMacchina
{
    public string $id;
    public string $username;
    public string $marca;
    public string $modello;
    public string $sede;
    public ?string $commento;
    public ?string $parcheggio;
    public DateTime $created_at;
    public ?DateTime $data_archivazione;
    public ?string $archiviata_da;
    public bool $disponibile;
    public bool $archiviata;

    /**
     * Constructor of the class 'Macchina'. The values of the properties are loaded
     *  from the array.
     *
     * @param array $properties Array containing the values of the properties 
     *  of the class instance, generated from table query
     */
    public function __construct(array $properties = array())
    {
        // Parse Strings
        $this->id = $properties['id'];
        $this->username = $properties['username'];
        $this->marca = $properties['marca'];
        $this->modello = $properties['modello'];
        $this->sede = $properties['sede'];
        $this->commento = $properties['commento'];
        $this->commento = $properties['archiviata_da'];
        $this->parcheggio = $properties['parcheggio'];
        // Parse Date Objects
        $this->created_at = new DateTime($properties['created_at']);
        $this->data_archivazione = new DateTime($properties['data_archivazione']);
        // Parse Bool
        $this->disponibile = filter_var($properties['disponibile'], FILTER_VALIDATE_BOOLEAN);
        $this->archiviata = filter_var($properties['archiviata'], FILTER_VALIDATE_BOOLEAN);
    }

    public function toArray()
    {
        return json_decode(json_encode($this), true);
    }
}