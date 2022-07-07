<?php

enum Sede
{
    case Torino;
    case Bologna;
    case Milano;
    case Empoli;
}


enum Motivazione
{
    case Aziendale;
    case Personale;
}

/**
 * Class mirrows a row in the database table 'prenotazioni'
 *
 * @author  David Henry Francis Wicker @ Mediamente Consulting
 * @license MIT
 */
class Prenotazione
{
    public int $id;
    public int $id_macchina;
    public string $username;
    public DateTime $from_date;
    public DateTime $to_date;
    public Sede $sede;
    public Motivazione $motivazione;
    public string $note;

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
}