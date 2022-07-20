<?php

/**
 * Class mirrows table `prenotazioni` in the database.
 * PHP Version 7.4.
 *
 * @author    David Henry Francis Wicker (https://github.com/davidwickerhf) <davidwickerhf@gmail.com>
 * @license   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */
class CPrenotazione
{
    public string $id;
    public string $id_macchina;
    public string $username;
    public DateTime $from_date;
    public DateTime $to_date;
    public DateTime $created_at;
    public string $motivazione;
    public ?string $commmento;

    /**
     * Constructor of the class 'Prenotazione'. The values of the properties are loaded
     *  from the array.
     *
     * @param array $properties Array containing the values of the properties 
     *  of the class instance, generated from table query
     */
    public function __construct(array $properties = array())
    {
        // Parse Strings
        $this->id = $properties['id'];
        $this->id_macchina = $properties['id_macchina'];
        $this->username = $properties['username'];
        $this->motivazione = $properties['motivazione'];
        $this->commento = $properties['commento'];
        // Parse Date Objects
        $this->from_date = new DateTime($properties['from_date']);
        $this->to_date = new DateTime($properties['to_date']);
        $this->created_at = new DateTime($properties['created_at']);
    }

    public function toArray()
    {
        return json_decode(json_encode($this), true);
    }
}