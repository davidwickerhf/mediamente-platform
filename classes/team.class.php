<?php

class Team {

    
    public function __construct($array=null) {
        
        global $db;
        
        $this->id_team= $array['id_team'] ?  $array['id_team'] : null;
        
        $team=$this->getTeam();
        
        $this->nome=$team->nome;
           
        
    }
  
    function getElencoTeam($filtro="")
    {
        global $db;
        return $db->get_results("SELECT * FROM team WHERE 1=1 $filtro ORDER BY nome");
    }
    
    function getTeam()
    {
        global $db;
        if(!$this->id_team)
            return null;
        return $db->get_row("SELECT * FROM team WHERE id=".$this->id_team);
    }
    
    function getUtentiTeam()
    {
        global $db;
        if(!$this->id_team)
            return null;
            return $db->get_results("SELECT t.*,CONCAT(CASE WHEN u.external=1 THEN '(EXT) ' ELSE '' END,u.cognome) cognome,u.nome FROM team_utenti t, utenti u WHERE t.username=u.username AND t.id_team=".$this->id_team." and (u.enabled=1 or u.external=1) ORDER BY u.cognome,u.nome");
    }
    
    function rimuoviUtente($username)
    {
        global $db;
        if(!$this->id_team)
            return null;
        return $db->query("DELETE FROM team_utenti WHERE id_team=".$this->id_team." AND username='$username'");
    }
    
    function aggiungiUtente($username)
    {
        global $db;
        if(!$this->id_team)
            return null;
       return $db->query("INSERT INTO team_utenti(id_team,username) VALUES(".$this->id_team.",'$username')");
    }
    
    function aggiungiTeam($nome)
    {
        global $db;
        $db->query("INSERT INTO team(nome) VALUES('$nome')");
        return $db->insert_id;
    }
    
    function rimuoviTeam()
    {
        global $db;
        if(!$this->id_team)
            return null;
         return $db->query("DELETE FROM team WHERE id=".$this->id_team);
    }
    
    function getNome()
    {
        return $this->nome;
    }
	
	function getElencoUtenti(){
		global $db;

        return $db->get_results("SELECT * FROM utenti WHERE enabled=1 OR external=1 ORDER BY cognome,nome");
	}
	
	function getUtentiTeamByUsername($utenti = ""){
		global $db;
		
		$filtro="";
		if($utenti<>"")
        {
            $in_user=implode("','", explode(",",$utenti));
            $filtro.=" AND username IN ('$in_user')";
        }

        return $db->get_results("SELECT * FROM utenti WHERE enabled=1 $filtro ORDER BY cognome,nome");
	}
}

?>