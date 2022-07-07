<?php

enum Tipologia
{
    case CambioGomme;
    case Tagliando;
    case Revisione;
}

/**
 * Class mirrows a row in the database table 'manutenzioni'
 *
 * @author  David Henry Francis Wicker @ Mediamente Consulting
 * @license MIT
 */
class Manutenzione
{
    public int $id;
    public int $id_macchina;
    public int $username;
    public DateTime $data;
    public Tipologia $tipologia;
    public string $luogo;
    public int $chilometri;

    /**
     * Constructor of the class 'Manutenzione'. The values of the properties are loaded
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