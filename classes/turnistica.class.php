<?php

class Turnistica {

    
    public function __construct($array=null) {
        
        global $db;
        
        require_once(ROOT_PATH."classes/progetto.class.php");
        require_once(ROOT_PATH."classes/calendario.class.php");
        
        $this->debug=0;
                 
        $this->bozza=new \stdClass();
        
        $this->rialloca=false;
        
        $this->bozza->id= $array['id_bozza'] ?  $array['id_bozza'] : null;
        
        $this->uniqsessid= $array['uniqsessid'] ?  $array['uniqsessid'] : uniqid();
        
    }
    
    function setProgetto($id)
    {
        $this->log("id_progetto: $id","SETUP");
        $this->id_progetto=$id;
        
        $progetto=new Progetto(Array('id'=>$this->id_progetto));
        
        $calendario=new Calendario(Array('id'=>$progetto->getProgetto()->{'id_calendario'}));
        
      
        
        $this->id_calendario=$calendario->id;
    }
    
    function setSettimana($settimana,$anno)
    {
        $this->log("Settimana: $settimana/$anno","SETUP");
        $this->settimana=$settimana;
        $this->anno=$anno;
        $calendario=new Calendario();
        $this->giorniFestivi=$calendario->giorniFestivi($this->anno);
        
        $this->log("Festivi: ".print_r($this->giorniFestivi,true));
    }
    
    function enableDebug($type="STDOUT")
    {
        /*
         * type:
         * -> STDOUT:   stampa a video il debug (default)
         * -> VAR:      memorizza il log nella variabile di sessione debugLog
         */
        
        
        $this->debug=1;
        
        if($type=="VAR") {
            $this->debug=2;
            $_SESSION['debugLog'] = "";
        }
        
    }
    
    
    function enableRialloca()
    {
        $this->rialloca=true;
    }
    
    function log($text,$act="INFO")
    {
        if(!$this->debug)
            return;
        
        if ($this->debug==1) 
            echo $act." - ".$text."\n";
        else 
            $this->setDebugLog($act." - ".$text."\n");
       
    }
    
    function setDebugLog($text)
    {
        if($this->debug != 2)
            return;
            
        if (isset($_SESSION['debugLog']))
            $_SESSION['debugLog'] .= $text;
        else 
            $_SESSION['debugLog'] = $text;
    }
    
    function getDebugLog()
    {
        if(!$_SESSION['debugLog'])
            return;
            
        return $_SESSION['debugLog'];
    }

    function aggiungiBozza()
    {
        global $db;
        
        $db->query("INSERT INTO progetto_turni_bozze(username,uniqsessid) VALUES('".getMyUsername()."','".$this->uniqsessid."')");
        $this->bozza->id=$db->insert_id;
        $this->creaTabellaTemporanea();
        
        return $this->bozza->id;
    }
    
    function caricaBozza()
    {
        global $db;
        
      
        $tableName="tmp_calendario_".$this->uniqsessid;
        $this->bozza->tableName=$tableName;
        
    }
    
    function approvaBozza()
    {
        global $db;
        
        $db->query("INSERT INTO calendario(titolo,descrizione,inizio,fine,username,modificato,id_turno,settimana_turno,anno_turno,id_calendario,id_progetto)
                          SELECT titolo,descrizione,inizio,fine,username,NOW(),id_turno,settimana_turno,anno_turno,id_calendario,id_progetto FROM ".$this->bozza->tableName."
                    WHERE uniqsessid='".$this->uniqsessid."'
                    ON DUPLICATE KEY UPDATE username=VALUES(username)
                    ");
        $this->eliminaTabellaTemporanea();
    }
    

    function getOwnerBozza()
    {
        global $db;
               
        $owner_bozza=$db->get_results("SELECT username FROM progetto_turni_bozze WHERE uniqsessid=".$this->uniqsessid." ORDER BY created DESC LIMIT 1");
        
        return $owner_bozza;
    }
    
    function creaTabellaTemporanea()
    {
        global $db;
        
       
        $tableName="tmp_calendario_".$this->uniqsessid;
        $this->bozza->tableName=$tableName;
        
        $this->log("Creo tabella temporanea $tableName","SETUP");
            
        $db->query("CREATE TABLE ".$this->bozza->tableName." AS 
        SELECT * FROM calendario WHERE inizio>=DATE_SUB(NOW(), INTERVAL 6 MONTH)
         " );
        
        $db->query("ALTER TABLE ".$this->bozza->tableName."  MODIFY id INT(11) AUTO_INCREMENT PRIMARY KEY");
        $db->query("ALTER TABLE ".$this->bozza->tableName." ADD `uniqsessid` VARCHAR(15) NULL");
    }
    
    
    function eliminaTabellaTemporanea()
    {
        global $db;
        
        $this->log("Elimino tabella temporanea $tableName","DELETE");
        
       // $db->query("DROP TABLE ".$this->bozza->tableName);
    }
    
       
    
    function pianificaSettimana()
    {
        global $db;
        
        if(!$this->id_progetto)
            return false;
        
        if(!$this->bozza->tableName)
        {
            $this->log("Bozza non caricata","ERROR");
            return false;
        }
        
        $gruppo_turni=$db->get_results("SELECT * FROM progetto_gruppo_turni WHERE id_progetto=".$this->id_progetto." ORDER BY priorita DESC");
        
        $dto = new DateTime();
        $dto->setISODate($this->anno, $this->settimana);
        $this->dataInizio=$dto->format('Y-m-d');
        
        
        $this->log("===== INIZIO PIANIFICAZIONE TURNI =====");
        $this->log("Inizio pianificazione settimana ".$this->anno."/".$this->settimana);
        
        foreach($gruppo_turni as $gt)
        {
            $this->pianificaGruppoTurni($gt);
        }
        
        $this->log("===== FINE PIANIFICAZIONE TURNI =====");
        
        
        $this->log("===== INIZIO CONTROLLO TURNI =====");
        foreach($gruppo_turni as $gt)
        {  
            if($gt->alloca_utente==1)
                $this->controllaAllocazioneGruppoTurni($gt);
            else 
                $this->log("Ignoro allocazione: non prevista per questo gruppo");
        }
        $this->log("===== FINE PIANIFICAZIONE TURNI =====");
        
        
        //$this->pulisciTabella();
        
        
        
        //$this->eliminaTabellaTemporanea();
        
        if(count($db->captured_errors))
            $this->log(print_r($db->captured_errors,true),"ERROR");
    }
    
    
    function controllaAllocazioneGruppoTurni($gt)
    {
        global $db;
        
        $this->log("Inizio controllo gruppo turni ".$gt->id);
        
        if($this->rialloca)
        {
            $this->log("Forzo riallocazione turni");
            $turnidaResettare=$db->get_results("SELECT * FROM ".$this->bozza->tableName." WHERE
                    id_turno IN(SELECT id FROM progetto_turni WHERE id_gruppo=".$gt->id.")
            AND settimana_turno=".$this->settimana." AND anno_turno=".$this->anno);
            foreach($turnidaResettare as $t)
            {
                $db->query("UPDATE ".$this->bozza->tableName." SET username=NULL WHERE id=".$t->id);
            }
        }
        
      
        
        $this->log("Tipo turni:".$gt->tipo_turni);
        
        $this->tipo_turni=$gt->tipo_turni;
        
        if($gt->tipo_turni=="ATOMICI")
        {
            $utenti=$db->get_results("SELECT t.username FROM team_utenti t, utenti u 
                     WHERE t.username=u.username AND u.enabled=1 AND t.id_team =".$gt->id_team);
            $this->utentiDisponibili=Array();
            foreach($utenti as $u)
                array_push($this->utentiDisponibili, $u->username);
                        
            $this->log("Devo allocare tutti i turni alla stessa persona.");
            do
            {
                $turniDaControllare=$db->get_results("SELECT * FROM ".$this->bozza->tableName." WHERE
                    id_turno IN(SELECT id FROM progetto_turni WHERE id_gruppo=".$gt->id.")
            AND settimana_turno=".$this->settimana." AND anno_turno=".$this->anno);
                
                $this->log("Turni da controllare:".print_r($turniDaControllare,true));
                $devoAllocare=false;
                $this->log("Utenti del team disponibili:".print_r($this->utentiDisponibili,true));
                foreach($turniDaControllare as $t)
                {
                    if(!$t->username)
                    {
                        $devoAllocare=true;
                    }
                    else 
                    {
                        if(!$this->controllaIncompatibilita($t))
                            $devoAllocare=true;
                    }
                }
                $this->log("Devo allocare? ".($devoAllocare ? "SI" : "NO"),"LOGIC");
                
                if($devoAllocare)
                    $this->allocaGruppoAtomico($gt,$this->calcolaUtenteIdeale($turniDaControllare[0], $this->utentiDisponibili));
            }
            while(count($this->utentiDisponibili)>0 && $devoAllocare==true);
        }
        elseif($gt->tipo_turni=="COMPOSTI")
        {
            $turniDaControllare=$db->get_results("SELECT * FROM ".$this->bozza->tableName." WHERE
                    id_turno IN(SELECT id FROM progetto_turni WHERE id_gruppo=".$gt->id.")
            AND settimana_turno=".$this->settimana." AND anno_turno=".$this->anno);
            
            $this->log("Devo allocare tutti i turni anche a persone diverse.");
            foreach($turniDaControllare as $t)
            {
                $devoAllocare=false;
                $utenti=$db->get_results("SELECT t.username FROM team_utenti t, utenti u 
                     WHERE t.username=u.username AND u.enabled=1 AND t.id_team =".$gt->id_team);
                $this->utentiDisponibili=Array();

                foreach($utenti as $u)
                    array_push($this->utentiDisponibili, $u->username);
                
                do{     // entra se non è stato allocato o se è incompatibile
                    if(!$t->username || !$this->controllaIncompatibilita($t))
                    {
                     $t=$this->allocaTurno($t,$this->calcolaUtenteIdeale($t, $this->utentiDisponibili));
                     $devoAllocare=true;
                    }
                    else
                        $devoAllocare=false;
                    
                }while(count($this->utentiDisponibili)>0 && $devoAllocare==true);
            }

           
        }
    }
    
    function allocaGruppoAtomico($gt,$username)
    {
        global $db;
        
        $this->log("Provo ad allocare gruppo turno ".$gt->id." a $username");
        
        if(!$username)
        {
            $this->log("nessun username","ERROR");
            return;
        }
        
        $db->query("UPDATE ".$this->bozza->tableName." SET 
                username='$username',
                uniqsessid= '".$this->uniqsessid."' 
                WHERE settimana_turno=".$this->settimana." 
                AND anno_turno=".$this->anno."
                AND id_turno IN(SELECT id FROM progetto_turni WHERE id_gruppo=".$gt->id.")");
        
    }
    
    function allocaTurno($t,$username)
    {
        global $db;
        
        $this->log("Provo ad allocare turno ".$t->id_turno." ".$t->inizio." a $username");
        
        if(!$username)
        {
            $this->log("nessun username","ERROR");
            return;
        }
        
        $db->query("UPDATE ".$this->bozza->tableName." SET
                username='$username',
                uniqsessid= '".$this->uniqsessid."'
                WHERE id=".$t->id);
        $t->username=$username;
        return $t;
        
    }
    
    function calcolaUtenteIdeale($t)
    {
        global $db;
        
        
        $in_utenti="'".implode("','", $this->utentiDisponibili)."'";
        
        $this->log("Utenti disponibili:".print_r( $this->utentiDisponibili,true));
           
        
        /*
         * Se sto cercando di allocare un turno notturno 
         * allora cerco chi non lo ha mai fatto o chi lo ha fatto meno recentemente.
         */
        
        $turni_notturni = $db->get_results("SELECT * FROM progetto_turni WHERE id_gruppo=1 AND id=".$t->id_turno."");  
        

        if (count($turni_notturni)) {
            
            //cerco utenti che non hanno mai fatto questo turno (id_turno)
            $utenti= $db->get_results("SELECT u.username 
                                        FROM utenti u 
                                        WHERE u.username IN($in_utenti) 
                                        AND u.username NOT IN(
                                            SELECT DISTINCT(t.username)
                                            FROM ".$this->bozza->tableName." t 
                                            WHERE t.id_turno=".$t->id_turno." 
                                            AND t.username IS NOT NULL)");
            
            if(count($utenti))
            {
                $this->log("Classifica utenti:".print_r($utenti,true));
                
                $username=$utenti[0]->{'username'};
                $this->log("$username non ha mai fatto un turno notturno.");
                $this->log("L'utente ideale trovato e' $username");
                return $username;
            }
            
            
            
            //cerco l'utente che ha fatto lo stesso tipo di turno in data piu' lontana.
            $utenti= $db->get_results("SELECT c.username FROM ".$this->bozza->tableName." c
             WHERE id_turno=".$t->id_turno." AND c.username IN($in_utenti) GROUP BY c.username ORDER BY MAX(fine) DESC");
            
            if(count($utenti)) {
                $utenti=array_reverse ($utenti);
                
                $this->log("Classifica utenti:".print_r($utenti,true));
                
                $username=$utenti[0]->{'username'};
                $this->log("$username ha fatto un turno notturno in data più lontana.");
                $this->log("L'utente ideale trovato e' ".$username,"LOGIC");
                return $username;
            }
            
            /* Vecchio blocco disabilitato
            if(!count($utenti))
            {
                if(count($this->utentiDisponibili))
                {
                    $username=array_shift(array_values($this->utentiDisponibili));
                    $this->log("L'utente ideale trovato e' $username");
                    return $username;
                }
                $this->log("Nessun utente");
                return null;
                
            }*/
        }

        //Fine
        
        
        // Inizio ricerca utente ideale per tutti gli altri turni (NON notturni)
        
        
        /*
         * Cerco l'utente che ha già fatto questo tipo di turno durante la settimana che si sta pianificando.
         * Provo quindi ad allocare lo stesso tipo di turno alla stessa persona per tutta la settimana
         */
        $turni_zero = $db->get_results("SELECT id FROM progetto_turni
            WHERE giorno=0 AND id_gruppo=(SELECT id_gruppo from progetto_turni where id=".$t->id_turno.")
            AND inizio='".date("H:i:s",strtotime($t->inizio))."'
            AND fine='".date("H:i:s",strtotime($t->fine))."'");
        
        
        $utente_zero= $db->get_results("SELECT username FROM ".$this->bozza->tableName."
        WHERE username IN($in_utenti)
        AND id_turno=".$turni_zero[0]->{'id'}."
        AND settimana_turno=".$this->settimana." AND anno_turno=".$this->anno."");
        
        if(count($utente_zero)) {
            $username=$utente_zero[0]->{'username'};
            $this->log("$username ha iniziato questo tipo di turno questa settimana.");
            $this->log("L'utente ideale trovato e' $username");
            return $username;
        }
        // Fine 
        
        

        /*
         * Cerco utenti che non hanno fatto neanche un turno nell'ultimo mese
         *
         */
        $utenti_unused= $db->get_results("SELECT t.username FROM utenti t 
        WHERE t.username IN($in_utenti)
        AND t.username NOT IN (
            SELECT DISTINCT(username) FROM ".$this->bozza->tableName."
            WHERE id_turno IS NOT NULL
            AND USERNAME IS NOT NULL 
            AND id_calendario=".$this->id_calendario."
            AND settimana_turno>=".$this->settimana."-6 
            AND settimana_turno < ".$this->settimana."
            AND anno_turno=".$this->anno.")");
        
        
        if(count($utenti_unused)) {
            $rand_key = array_rand($utenti_unused, 1); // seleziona una chiave in maniera random
            $username=$utenti_unused[$rand_key]->{'username'}; //prendi un utente a caso tra quelli che non hanno mai fatto un turno la settimana prima
            $this->log("$username non ha fatto turni nell'ultimo mese.");
            $this->log("L'utente ideale trovato e' $username");
            
            $this->log("Classifica utenti:".print_r($utenti_unused,true));
            
            return $username;
        }
        // Fine 
        
        
        /*
         * Cerco l'utente che ha fatto meno turni nell'ultimo mese.
         * 
         */
        
        //La query calcola un solo turno per giorno per utente. Quindi il turno notturno viene contato una sola volta per giorno
        $utenti_underused= $db->get_results("SELECT c.username, COUNT(c.username) as tot
            FROM (
                SELECT t.username, DATE(t.inizio)
                FROM ".$this->bozza->tableName." t
                WHERE t.id_turno IS NOT NULL
                AND t.username IN($in_utenti)
                AND id_calendario=".$this->id_calendario."
                AND settimana_turno>=".$this->settimana."-6 
                AND settimana_turno < ".$this->settimana."
                AND anno_turno=".$this->anno."
                GROUP BY DATE(t.inizio), t.username
            ) c
            GROUP BY c.username
            ORDER BY tot ASC");
        
        
        //Prendi solo gli utenti che hanno fatto lo stesso minor numero di turni
        $utenti_uu = array();
        $prec = 999999999;
        foreach ($utenti_underused as $user_uu) {
            if ($user_uu->tot > $prec)
                break;
            
            array_push($utenti_uu,$user_uu->username);
            $prec = $user_uu->tot;
        }
        
        $rand_key = array_rand($utenti_uu, 1); // seleziona una chiave in maniera random
        $username=$utenti_uu[$rand_key]; //prendi un utente a caso tra quelli che non hanno mai fatto un turno la settimana prima
        $this->log("$username ha fatto meno turni tra tutti nell'ultimo mese.");
        $this->log("L'utente ideale trovato e' $username");
            
        $this->log("Utenti con meno turni:".print_r($utenti_uu,true));
        $this->log("Classifica utenti:".print_r($utenti_underused,true));
        
            
        return $username;
        // Fine 
                
    }
    
    function controllaIncompatibilita($t)
    {
        global $db;
        
        $date=date("Y-m-d",strtotime($t->inizio));
        
        $this->log("Controllo compatibilita ".$t->id);
        
        //CASI DI INCOMPATIBILITA CON $t CON ALTRO
        //1. impegno bloccante sovrapposto
        
        if($db->get_var("SELECT COUNT(*) FROM ".$this->bozza->tableName." WHERE id<>".$t->id."
                 AND 
                (
                (inizio<'".$t->inizio."' AND fine>'".$t->inizio."')
                OR
                (inizio>='".$t->inizio."' AND fine<='".$t->fine."')
                OR
                (inizio<'".$t->fine."' AND fine>'".$t->fine."')
                )
                AND id_progetto IS NOT NULL
                AND bloccante=1
                AND username='".$t->username."'
        ")>0)
        {
            $this->log("Turno incompatibile caso 1","LOGIC");
            if (($key = array_search($t->username, $this->utentiDisponibili)) !== false)
                unset($this->utentiDisponibili[$key]);
            return false;
        }
        
        //2. Altro turno nella stessa giornata per lo stesso progetto (SOLO COMPOSTI)
        
        
        if($this->tipo_turni=="COMPOSTI")
        {
            if($db->get_var("SELECT COUNT(*) FROM ".$this->bozza->tableName." WHERE id<>".$t->id."
                    AND (
                    inizio>='$date 00:00:00' AND fine<='$date 23:59:59'
                    )
                    AND id_progetto=".$t->id_progetto."
                    AND username='".$t->username."'
            ")>0)
            {
                $this->log("Turno incompatibile caso 2","LOGIC");
                if (($key = array_search($t->username, $this->utentiDisponibili)) !== false)
                    unset($this->utentiDisponibili[$key]);
                return false;
            }
        }
        
       
        
        $this->log("Turno compatibile","LOGIC");
         
        return true;
    }
    
    

    
    
    
    function pianificaGruppoTurni($gt)  //OK
    {
        global $db;
        
        $id_eventi_gruppo=Array();
        
        $this->log("=INIZIO PIANIFICAZIONE GRUPPO TURNI ".$gt->id."=");
        $this->log(print_r($gt,true));
        
        
        $this->dataInizioGruppoTurni=$this->dataInizio;
        for($i=0;$i<=7;$i++)
        {
            if(date("N",strtotime($this->dataInizio)+($i*86400))==$gt->giorno_inizio)
            {
                $this->dataInizioGruppoTurni=date("Y-m-d",strtotime($this->dataInizio)+($i*86400));
                $this->dataFineGruppoTurni=date("Y-m-d",strtotime($this->dataInizioGruppoTurni)+(7*86400));
                break;
            }
        }
        
        $this->log("Pianifico gruppo ".$gt->id);
        $this->log("Data inizio: ".$this->dataInizioGruppoTurni);
        $this->log("Data fine: ".$this->dataFineGruppoTurni);
        
       
        for($i=0;$i<=7;$i++) //pianifico una settimana di questo turno
        {
            
            $date=date("Y-m-d",strtotime($this->dataInizioGruppoTurni)+(86400*$i));
            $this->log("Pianifico data $date");
            
            $filtro="";
            $inizio="";
            $fest="";
            if (isset($this->giorniFestivi[$date]))
            {
                $this->log("$date festivo","LOGIC");
                $fest = "_festivo";
            }
                
            
            $turni_da_pianificare = $db->get_results("SELECT id,id_gruppo,nome,giorno,inizio$fest as inizio,fine$fest as fine FROM progetto_turni 
                WHERE id_gruppo=" . $gt->id . " 
                AND giorno='" . $i . "'
                ORDER BY inizio ASC");
            
            if (count($turni_da_pianificare)) {
                foreach ($turni_da_pianificare as $turno_da_pianificare) {
                    if($turno_da_pianificare->inizio && $turno_da_pianificare->fine)
                    {
                        $this->log("Turno da pianificare:" . print_r($turno_da_pianificare, true));
                        
                        $id_evento_turno=$this->esisteTurno($date, $turno_da_pianificare);
                        if (! $id_evento_turno)
                        {
                            $id_evento_turno=$this->aggiungiTurno($date, $turno_da_pianificare);
                        }
                        else
                            $this->log("Il turno e' gia' presente in tabella.","LOGIC");
                        
                        array_push($id_eventi_gruppo, $id_evento_turno);
                    }
                    else
                        $this->log("Salto questa pianificazione per NULL inizio/fine","LOGIC");
                }
            } else
                $this->log("Per questa data non esiste un turno.","LOGIC");
        }
        
    }
        
   
    function esisteTurno($date,$t)
    {
        global $db;
        $id=$db->get_var("SELECT id FROM ".$this->bozza->tableName."
                WHERE id_turno=".$t->id." 
                AND inizio= '$date ".$t->inizio."'
                AND fine='$date ".$t->fine."'");
        if($id)
            return $id;
        return false;
    }
    
    function aggiungiTurno($date,$t)
    {
        global $db;
        
        $this->log("Aggiungo turno ".$t->id." data $date","CREATE");
        
      
        
        $db->query("INSERT INTO ".$this->bozza->tableName." (titolo,descrizione,inizio,fine,modificato,username,id_turno,id_calendario,settimana_turno,anno_turno,id_progetto,uniqsessid)
                    VALUES(
                        '".$t->nome."',
                        'Evento creato automaticamente dal cervello robotico di Saverio',
                        '$date ".$t->inizio."',
                        '$date ".$t->fine."',
                        NOW(),
                        NULL,
                        '".$t->id."',
                        '".$this->id_calendario."',
                        ".$this->settimana.",
                        ".$this->anno.",
                        '".$this->id_progetto."',
                        '".$this->uniqsessid."'
                    )");
        
           
        return $db->insert_id;
    }
    
   
    
    function pulisciTabella()
    {
        global $db;
        $db->query("DELETE FROM ".$this->bozza->tableName." WHERE uniqsessid IS NULL OR uniqsessid<>'".$this->uniqsessid."'");
    }
    
    
}

?>