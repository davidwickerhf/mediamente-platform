<?php

/**
 * Class mirrows table `manutenzioni` in the database.
 * PHP Version 7.4.
 *
 * @author    David Henry Francis Wicker (https://github.com/davidwickerhf) <davidwickerhf@gmail.com>
 * @license   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */
class CManutenzione
{
    public string $id;
    public string $id_macchina;
    public string $username;
    public DateTime $data;
    public DateTime $created_at;
    public string $tipologia;
    public string $luogo;
    public string $chilometri;
    public ?string $commento;

    /**
     * Constructor of the class 'Manutenzione'. The values of the properties are loaded
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
        $this->tipologia = $properties['tipologia'];
        $this->luogo = $properties['luogo'];
        $this->chilometri = $properties['chilometri'];
        $this->commento = $properties['commento'];
        // Parse Date Objects
        $this->data = new DateTime($properties['data']);
        $this->created_at = new DateTime($properties['created_at']);
        $this->chilometri = intval($properties['chilometri']);
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