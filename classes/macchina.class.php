<?php

enum Sede
{
    case Torino;
    case Bologna;
    case Milano;
    case Empoli;
}

/**
 * Class mirrows a row in the database table 'macchine'
 *
 * @author  David Henry Francis Wicker @ Mediamente Consulting
 * @license MIT
 */
class Macchina
{
    public int $id;
    public string $modello;
    public Sede $sede;
    public bool $disponibile;
    public bool $archiviata;
    public DateTime $ultima_revisione;
    public DateTime $ultimo_tagliando;
    public DateTime $ultimo_cambio_gomme;
    public DateTime $data_registrazione;
    public DateTime $data_archivazione;
    public string $registrata_da;
    public string $archiviata_da;

    /**
     * Constructor of the class 'Macchina'. The values of the properties are loaded
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