<?php

class Progetto {

    public function __construct($array=null) {
        
        global $db;
       
        $this->id= $array['id'] ?  $array['id'] : null;
        
        if($this->id)
            $this->caricaProgetto();
        
        
    }
    
    function caricaProgetto()
    {
        global $db;
        if(!$this->id)
            return;
        $progetto=$db->get_row("SELECT * FROM progetti WHERE id=".$this->id);
        $this->progetto=$progetto;
    }
    
    function getElencoProgetti($filtro)
    {
        global $db;
        return $db->get_results("SELECT p.*,c.nome as cliente_nome FROM progetti p,clienti c WHERE p.codice_cliente=c.codice_cliente $filtro ORDER BY nome");
    }
    
    function getProgetto()
    {
        global $db;
        return $this->progetto;
    }
    
    function getUtenti()
    {
        global $db;
        return $db->get_results("SELECT u.username,u.nome,u.cognome FROM utenti u WHERE (u.enabled=1 OR u.external=1) AND u.username IN(
                SELECT username FROM team_utenti WHERE id_team IN(SELECT id_team FROM progetti_team WHERE id_progetto=".$this->id.")
        ) ORDER BY u.cognome,u.nome");
    }
    
    function getTeam()
    {
        global $db;
        return $db->get_results("SELECT * FROM team WHERE id IN(SELECT id_team FROM progetti_team WHERE id_progetto=".$this->id.") ORDER BY nome");
    }
    
    function exists()
    {
        if($this->progetto->id)
            return true;
        return false;
    }
    
    function getElencoProgettiCalendario($id_calendario,$filtro=null)
    {
        return $this->getElencoProgetti("AND id_calendario=$id_calendario $filtro");
    }
    
    function getElencoProgettiTeam($id_team,$filtro=null)
    {
        return $this->getElencoProgetti("AND id IN(SELECT id_progetto FROM progetti_team WHERE id_team=$id_team) $filtro");
    }
    
    function getElencoProgettiUtente($username,$filtro=null)
    {
        return $this->getElencoProgetti("AND id IN(SELECT id_progetto FROM progetti_team WHERE id_team IN(SELECT id_team FROM team_utenti WHERE username='$username')) $filtro");
    }
      
    
    function setIDProgetto($id)
    {
        $this->id=$id;
    }
    
    
    function aggiungiProgetto()
    {
        global $db;
        $db->query("INSERT INTO progetti(id) VALUES(NULL)");
        $this->id= $db->insert_id;
        return $this->id;
    }
    
    function salvaProgetto($values)
    {
        global $db;
        $query="";
        foreach($values as $key=>$val)
            $query.=$key."='".$val."',";
        
        $query=rtrim($query,",");
        $db->query("UPDATE calendario SET id_calendario=".$values['id_calendario']." WHERE id_progetto=".$this->id);
        return $db->query("UPDATE progetti SET $query WHERE id=".$this->id);
    }
    
    function rimuoviProgetto()
    {
        global $db;
        if(!$this->id)
            return null;
        return $db->query("DELETE FROM progetti WHERE id=".$this->id);
    }
    
    function aggiungiTeam($id_team)
    {
        global $db;
        return $db->query("INSERT INTO progetti_team(id_progetto,id_team) VALUES(".$this->id.",$id_team) ON DUPLICATE KEY UPDATE id_progetto=VALUES(id_progetto)");    
    }
    
    function rimuoviTeam($id_team)
    {
        global $db;
        return $db->query("DELETE FROM progetti_team WHERE id_progetto=".$this->id." AND id_team=$id_team");
    }
    
    function getNome()
    {
        return $this->progetto->nome;
    }
    
}

?>