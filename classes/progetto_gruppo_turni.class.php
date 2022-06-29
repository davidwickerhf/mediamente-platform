<?php

class ProgettoGruppoTurni {

    public function __construct($array=null) {
        
        global $db;
       
        $this->id= $array['id'] ?  $array['id'] : null;
        
        if($this->id)
            $this->caricaProgettoGruppoTurni();
        
        
    }
    
    function caricaProgettoGruppoTurni()
    {
        global $db;
        if(!$this->id)
            return;
        $progetto=$db->get_row("SELECT * FROM progetto_gruppo_turni WHERE id=".$this->id);
        $this->progetto=$progetto;
    }
    
    function getElencoProgettiGruppoTurni($filtro="")
    {
        global $db;
        return $db->get_results("SELECT pgt.id_team, t.nome nome_team, pgt.id_progetto, p.codice_cliente, p.id_commessa, p.nome nome_progetto, pgt.id, pgt.nome nome_progretto_turni, pgt.priorita, pgt.tipo_turni, pgt.giorno_inizio, pgt.alloca_utente 
                                    FROM progetto_gruppo_turni pgt
                                    JOIN team t on (t.id = pgt.id_team)
                                    JOIN progetti p on (p.id = pgt.id_progetto)
                                    WHERE 1=1 $filtro
                                    ORDER BY pgt.id");
    }

    function getElencoProgetti($filtro="")
    {
        global $db;
        return $db->get_results("SELECT id, nome
                                    FROM progetti
                                    WHERE 1=1 $filtro
                                    ORDER BY id");
    }

    function getElencoTeam($filtro="")
    {
        global $db;
        return $db->get_results("SELECT id, nome
                                    FROM team
                                    WHERE 1=1 $filtro
                                    ORDER BY id");
    }

    function rimuoviProgettoGruppoTurni()
    {
        global $db;
        if(!$this->id)
            return null;
        return $db->query("DELETE FROM progetto_gruppo_turni WHERE id=".$this->id);
    }

    function rimuoviTurno($id)
    {
        global $db;
        return $db->query("DELETE FROM progetto_turni WHERE id=$id");
    }

    function aggiungiProgettoGruppoTurno()
    {
        global $db;
        $db->query("INSERT INTO progetto_gruppo_turni(id) VALUES(NULL)");
        $this->id= $db->insert_id;
        return $this->id;
    }
    
    function salvaProgettoGruppoTurno($values)
    {
        global $db;
        $query="";
        foreach($values as $key=>$val)
            $query.=$key."='".$val."',";
            
        $query=rtrim($query,",");
        return $db->query("UPDATE progetto_gruppo_turni SET $query WHERE id=".$this->id);
    }

    function aggiungiTurno($values)
    {
        global $db;
        $db->query("INSERT INTO progetto_turni(id_gruppo, nome, giorno, inizio, fine, inizio_festivo, fine_festivo)
                    VALUES (".$values['id_gruppo'].",'".$values['nome']."','".$values['giorno']."','".$values['inizio']."','".$values['fine']."',
                    ".($values['inizio_festivo'] ? "'".$values['inizio_festivo']."'" : "NULL").",".($values['fine_festivo'] ? "'".$values['fine_festivo']."'" : "NULL").")");
        $id= $db->insert_id;
        return $id;
    }
    
    function getProgettoGruppoTurni()
    {
        global $db;
        return $this->progetto;
    }

    function getTurni($filtro=""){
        global $db;
        if(!$this->id)
            return null;
        return $db->get_results("SELECT id, id_gruppo, nome, giorno, inizio, fine, inizio_festivo, fine_festivo
                                    FROM progetto_turni
                                    WHERE id_gruppo = ".$this->id ." $filtro
                                    ORDER BY id");
    }
    
    function getNome()
    {
        return $this->progetto->nome;
    }

    function getId()
    {
        return $this->id;
    }
    
    function getIdProgetto()
    {
        return $this->progetto->id_progetto;
    }
    
    function exists()
    {
        if($this->progetto->id)
            return true;
         return false;
    }
    
    
    function getIdCliente()
    {
        global $db;
        return $db->get_var("SELECT codice_cliente FROM progetti WHERE id=".$this->progetto->id_progetto);
    }
}

?>