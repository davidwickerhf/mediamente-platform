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
    public $id;
    public $id_macchina;
    public $username;
    public $from_date;
    public $to_date;
    public $sede;
    public $motivazione;
    public $note;
    public $commmento;

    /**
     * Constructor of the class 'Prenotazione'. The values of the properties are loaded
     *  from the array.
     *
     * @param array $properties Array containing the values of the properties 
     *  of the class instance, generated from table query
     */
    public function __construct(array $properties = array())
    {
        foreach ($properties as $key => $value) {
            $this->{$key} = $value;
        }
    }

    // Class Getter
    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    // Class Setter
    public function __set($property, $value)
    {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }

        return $this;
    }

    public function toArray()
    {
        return json_decode(json_encode($this), true);
    }
}