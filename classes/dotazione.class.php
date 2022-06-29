<?php

class Dotazione
{

    public function __construct($array = null)
    {
        global $db;
        
     
    }

    
    function getDotazioniPersonali()
    {
        global $db;
        return $db->get_results("SELECT * FROM pcaziendali WHERE username='".getMyUsername()."' ORDER BY asset_tag DESC");
    }
    
}

?>