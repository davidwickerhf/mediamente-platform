<?php

class Calendario {
    
    public $start;
    public $end;
    
    public function __construct($array=null) {
        
        global $db;
        
        $this->start = $array['start'] ? $array['start'] : null;
        $this->end = $array['end']  ? $array['end'] : null;
        $this->title=$array['id'] ? $db->get_var("SELECT nome FROM calendario_calendari WHERE id=".$array['id']) : "personale";
        $this->id= $array['id'] ?  $array['id'] : null;
        $this->titleFormat= $array['titleFormat'] ?  $array['titleFormat'] : "username";
        $this->filtroID=true;
        
        if($this->id)
            $this->caricaCalendario();
        
        if(isset($this->calendario->tipo) && $this->calendario->tipo=="personale")
        {
            $this->titleFormat="projectName";
            $this->calendario->colore=null;
            $this->calendario->colore_testo=null;
        }
        
    }
    
    function getElencoCalendari($filtro)
    {
        global $db;
        return $db->get_results("SELECT cc.id, CONCAT(CASE WHEN u.external = 1 THEN '(EXT) ' ELSE '' END, cc.nome) nome, cc.colore, cc.colore_testo, cc.tipo, cc.username, cc.updated FROM calendario_calendari cc LEFT JOIN utenti u on (cc.username = u.username) WHERE (IFNULL(u.enabled,1) = 1 OR u.external = 1) $filtro ORDER BY cc.nome");
    }
    
    function getTurni($filtro=""){
        global $db;
        if(!$this->start || !$this->end)
            return null;
        
        if($this->calendario->tipo=="generico")
            $filtro.=" AND id_calendario=".$this->id;
       
        if($this->calendario->username)
            $filtro.=" AND username='".$this->calendario->username."'";
        
        if($this->filtro)
            $filtro.=" ".$this->filtro." ";
        
        
        return $db->get_results("SELECT * FROM calendario WHERE 1=1 $filtro AND 
                    (
                    (inizio<='".$this->start."' AND fine>='".$this->start."')
                        OR
                    (inizio>='".$this->start."' AND fine<='".$this->end."')
                         OR
                    (inizio<='".$this->end."' AND fine>='".$this->end."')
                    )
             ORDER BY inizio");
    }
    
    function giorniFestivi($anno)
    {
        $array_festivita = array(
            "01-01" => "Primo dell'anno",
            "01-06" => "Epifania",
            "04-25" => "Festa della liberazione",
            "05-01" => "Festa dei lavoratori",
            "06-02" => "Festa della repubblica",
            "08-15" => "Ferragosto",
            "11-01" => "Festa di tutti i santi",
            "12-08" => "Festa dell'immacolata",
            "12-25" => "Natale",
            "12-26" => "Giorno di Santo Stefano"
        );
        
      $return=Array();
      
      foreach ($array_festivita as $f=>$g)
          $return[$anno."-".$f] =$g;
      
      $return[date("Y-m-d",easter_date($anno))]="Pasqua";
      $return[date("Y-m-d",easter_date($anno)+86400)]="Pasquetta";
      return $return;
      
    }
    
    function getFestivita($filtro=""){
        global $db;
        
        if(!$this->start || !$this->end)
            return null;
        
        if(!$this->calendario->colore)
            $this->calendario->colore='#911616';
        
        $anno_inizio=date("Y",strtotime($this->start));
        $anno_fine=date("Y",strtotime($this->end));
       
        $festivita=$this->giorniFestivi($anno_inizio);
        
        $return=Array();
        foreach ($festivita as $giorno=>$festa)
        {
            $row=null;
            $row->inizio=$giorno." 00:00:00";
            $row->fine=$giorno." 23:59:59";
            $row->titolo=$festa;
            //echo "-".dateSovrapposte($row->inizio,$this->start,$this->end);
            if(dateSovrapposte($row->inizio,$this->start,$this->end))
                array_push($return, $row);
           
        }
        
        if($anno_fine!=$anno_inizio)
        {
            $festivita=$this->giorniFestivi($anno_fine);
            foreach ($festivita as $giorno=>$festa)
            {
                $row= new \stdClass();
                $row->inizio=$giorno." 00:00:00";
                $row->fine=$giorno." 23:59:59";
                $row->titolo=$festa;
                if(dateSovrapposte($row->inizio,$this->start,$this->end))    
                 array_push($return, $row);
                
            }
        }
     
        
        return $return;
    }
    
    function getTurno($id){
        global $db;
        return $db->get_row("SELECT * FROM calendario WHERE id=$id");
    }
    
    function setIDCalendario($id)
    {
        $this->id=$id;
    }
    
    function setUsername($username)
    {
        $this->username=$username;
        $this->calendario->username=$username;
    }
    
    function setIntervallo($start,$end)
    {
        $this->start=$start;
        $this->end=$end;
    }
    
    function setFormatoTitolo($titleFormat)
    {
        $this->titleFormat=$titleFormat;
    }
    
    function getTipoCalendario()
    {
        return $this->calendario->tipo;
    }
    
    function getUsername()
    {
        return $this->calendario->username;
    }
    
    function aggiungiCalendario()
    {
        global $db;
        $db->query("INSERT INTO calendario_calendari(id) VALUES(NULL)");
        $this->id=$db->insert_id;
        return $this->id;
    }

    function salvaCalendario($values)
    {
        global $db;
        $query="";
        foreach($values as $key=>$val)
        $query.=$key."='".$val."',";
        
        $query=rtrim($query,",");
        return $db->query("UPDATE calendario_calendari SET $query WHERE id=".$this->id);
    }
    
    function rimuoviCalendario()
    {
        global $db;
        if(!$this->id)
            return null;
        return $db->query("DELETE FROM calendario_calendari WHERE id=".$this->id);
    }
    
    function getCalendario()
    {
        global $db;
        return $this->calendario;
    }
    
    function getCalendarioUtente($username)
    {
        global $db;
        return $db->get_row("SELECT * FROM calendario_calendari WHERE tipo='personale' AND username='$username'");
    }
    
    function getCalendarioProgetto($id_progetto)
    {
        global $db;
        return $db->get_row("SELECT * FROM calendario_calendari WHERE id=(SELECT id_calendario FROM progetti WHERE id=$id_progetto)");
    }
    
    function caricaCalendario()
    {
        global $db;
        if(!$this->id)
            return;
        $this->calendario=$db->get_row("SELECT * FROM calendario_calendari WHERE id=".$this->id);
    }
    
    function exists()
    {
        if($this->calendario->id)
            return true;
        return false;
    }
    
    function getTitle()
    {
        return $this->title;
    }
    
    function getNome()
    {
        return $this->getTitle();
    }
    
    function importOutlook()
    {
        //https://outlook.office365.com/mail/options/calendar/SharedCalendars/publishedCalendars
    }
    
    
    function aggiungiEvento()
    {
        global $db;
        $db->query("INSERT INTO calendario(id) VALUES(NULL)");
        return $db->insert_id;
    }
    
    function salvaEvento($values)
    {
        global $db;
        $query="";
        foreach($values as $key=>$val)
            $query.=$key."='".$val."',";
            
        $query=rtrim($query,",");
        
        if($values['id_calendario'])
            $db->query("UPDATE calendario_calendari SET updated=NOW() WHERE id=".$values['id_calendario']);
        
        return $db->query("UPDATE calendario SET $query,modificato=NOW() WHERE id=".$values['id']);
    }
    
    
    function formattaTurno($cal)
    {
        global $db;
		$recur=$cal->ricorsivo;
		$daysOfWeek=Array();
        $dateFormat="Y-m-d H:i";
        $start= $cal->inizio;
        $end= $cal->fine;
        if (strpos($cal->inizio, "00:00:00") !== false && strpos($cal->fine, "23:59:59") !== false) {
            $turno['allDay'] = "true";
            $end=date("Y-m-d H:i:s",strtotime($cal->fine)+1);
            $dateFormat="Y-m-d";
        }
        if($cal->id_progetto)
         $progetto=$db->get_row("SELECT * FROM progetti WHERE id=".$cal->id_progetto);
         if($this->titleFormat=="username" && $cal->username)
            $turno['title'] = $cal->username." ".$cal->titolo;
        elseif($this->titleFormat=="projectName" && $cal->id_progetto)
            $turno['title'] =$progetto->nome." ".$cal->titolo;
        else 
            $turno['title']=$cal->titolo;
        
        $turno['title'] = html_entity_decode($turno['title'], ENT_QUOTES , 'UTF-8');
        if($recur!="0000000" and !is_null($recur)){
			for($i=0;$i<strlen($recur);$i++){
				if($recur[$i]=="1"){
					$daysOfWeek[]=($i+1)%7;
				}
			}
            $turno['startRecur'] = date("Y-m-d",strtotime($cal->inizio));
            $turno['endRecur'] = date("Y-m-d",strtotime($cal->fine));
            $turno['startTime'] = $turno['allDay']=="true" ? date("H:i",strtotime("2000-01-01 09:00:00")) : date("H:i",strtotime($cal->inizio));
            $turno['endTime'] = $turno['allDay']=="true" ? date("H:i",strtotime("2000-01-01 18:00:00")) : date("H:i",strtotime($cal->fine)+1);
			$turno['daysOfWeek'] =$daysOfWeek;
			unset($turno['allDay']);
        }else{
            $turno['start'] = $start;
            $turno['end'] = $end;
        }
        $turno['id'] = $cal->id;
        $turno['username'] = $cal->username;
        $turno['id_calendario'] = $cal->id_calendario;
        $turno['bloccante'] = $cal->bloccante;
		$turno['inserito_da'] = $cal->inserito_da;
        $turno['descrizione'] =  html_entity_decode($cal->descrizione, ENT_QUOTES , 'UTF-8');
        $turno['titolo'] = html_entity_decode($cal->titolo, ENT_QUOTES , 'UTF-8');
        $turno['id_progetto'] = $cal->id_progetto;
        if($progetto)
            $turno['codice_cliente'] = $progetto->codice_cliente;
        $turno['inizio'] = date($dateFormat,strtotime($cal->inizio));
        $turno['fine'] = date($dateFormat,strtotime($cal->fine));
        
        $turno['backgroundColor'] = $this->getColore($cal);
        $turno['textColor'] = $this->getColoreTesto($cal);
        
        $turno['hours'] = round((strtotime($end)-strtotime($start))/3600,1);
		$turno['ricorsivo'] = $recur;
        return $turno;
    }

    function formattaTurni($turni)
    {
        $out = Array();
        foreach ($turni as $cal) :
           
            $out[] = $this->formattaTurno($cal);
        endforeach
        ;
        
        return $out;
    }
    
    function rimuoviEvento($id)
    {
        global $db;
        return $db->query("DELETE FROM calendario WHERE id=".$id);
    }
    
    
 
    function getColore($turno)
    {
        global $db;
        if(isset($this->calendario->colore))
            return $this->calendario->colore;
        if(!$turno->id_calendario)
            return "#000000";
        
        return $db->get_var("SELECT colore FROM calendario_calendari WHERE id=".$turno->id_calendario);
    }
    
    function getColoreTesto($turno)
    {
        global $db;
        if(isset($this->calendario->colore_testo))
            return $this->calendario->colore_testo;
        
        if(!$turno->id_calendario)
            return "#ffffff";
            
        return $db->get_var("SELECT colore_testo FROM calendario_calendari WHERE id=".$turno->id_calendario);
    }
    
    
    
    function calendarioAMSSync()
    {
        global $db;
        
        $db->query("DELETE FROM calendario WHERE id_calendario=1 AND id_calendario_esterno IS NULL AND inizio> DATE_SUB(NOW(), INTERVAL 2 DAY)");
        $turni=$db->get_results("SELECT * FROM ams.calendario_reperibilita WHERE inizio> DATE_SUB(NOW(), INTERVAL 2 DAY)");
        if (is_array($turni) || is_object($turni)){
            foreach($turni as $turno)
            {
                if($turno->livello==1)
                {
                    switch($turno->tipo)
                    {
                        case "4":
                            $id_turno=39;
                            break;
                        case "3":
                            $id_turno=31;
                            break;
                        case "2":
                            $id_turno=22;
                            break;
                        case "1":
                            $id_turno=33;
                            break;
                        case "0":
                            if(strpos($turno->fine,"09:30:00")!==false)
                                $id_turno=11;
                            elseif(strpos($turno->inizio,"17:30:00")!==false)
                                $id_turno=17;
                            else
                                $id_turno=14;
                            break;
                    }
                }
                else
                    $id_turno=54;
                
                
                $db->query("INSERT INTO calendario(titolo,inizio,fine,username,id_calendario,id_progetto,id_turno)
                            VALUES(
                                    'Turno',
                                    '".$turno->inizio."',
                                    '".$turno->fine."',
                                    '".$turno->username."',
                                    1,
                                    7,
                                    $id_turno
                                )");
        }
    }
        $db->query("UPDATE calendario_calendari SET updated=NOW() WHERE id=1");
    }
    
    
    function importaCalendariEsterni($filtro="")
    {
        global $db;
        foreach($db->get_results("SELECT id FROM calendari_esterni WHERE 1=1 $filtro") as $cal)
            $this->importaCalendarioEsterno($cal->id);
    }
    
    
    function importaCalendarioEsterno($id)
    {
        require_once ROOT_PATH.'helpers/ical.php';
        global $db;
        
        $calendario=$db->get_row("SELECT * FROM calendari_esterni WHERE id=$id");
        
       
        $cal_personale=$this->getCalendarioUtente($calendario->username);
        
        
        $iCal = new iCal($calendario->url);
        
        
        $events = $iCal->eventsByDate();
        // or :
        // $events = $iCal->eventsByDateBetween('2014-01-01', '2015-01-01');
        // or :
        // $events = $iCal->eventsByDateSince('2014-01-01');
        // or :
        // $events = $iCal->eventsByDateSince('today');
        
    
        $deleteQuery="DELETE FROM calendario WHERE id_calendario_esterno=".$calendario->id." AND id_calendario=".$cal_personale->id." AND uid_evento_esterno NOT IN(";
        foreach ($events as $date => $events)
        {
          
            foreach ($events as $event)
            { 
               $deleteQuery.="'".$event->uid."',";
               $db->query("
                    INSERT INTO calendario(titolo,descrizione,inizio,fine,username,id_calendario,id_calendario_esterno,uid_evento_esterno,bloccante)
                    VALUES(
                    'EXT ".addslashes($calendario->nome." - ".$event->summary)."',
                    '".addslashes(str_replace("\\n","\n",$event->description))."',
                    '".addslashes($event->dateStart)."',
                    '".addslashes($event->dateEnd)."',
                    '".$calendario->username."',
                    ".$cal_personale->id.",
                    ".$calendario->id.",
                    '".addslashes($event->uid)."',
                    0
                )
                ON DUPLICATE KEY UPDATE titolo=VALUES(titolo),inizio=VALUES(inizio),fine=VALUES(fine),descrizione=VALUES(descrizione)
                ");
            }
        }
        $deleteQuery=rtrim($deleteQuery,",");
        $deleteQuery.=")";
        
        $db->query("UPDATE calendario_calendari SET updated=NOW() WHERE id=".$cal_personale->id);
        $db->query($deleteQuery);

        
    }
    
    function getSovrapposizioni($inizio,$fine,$username,$id=null,$ricorsivo='0000000')
    {
        global $db;
        $filtro_id="";
        if($id)
            $filtro_id="AND id<>$id";
        return $db->get_results("SELECT * FROM calendario WHERE username='$username' $filtro_id
            AND 
            (
                    (inizio<='".$inizio."' AND fine>'".$inizio."')
                        OR
                    (inizio>='".$inizio."' AND fine<='".$fine."')
                         OR
                    (inizio<'".$fine."' AND fine>'".$fine."')
             )
           AND BINARY 
             CASE WHEN ((DAYOFWEEK('".$inizio."')+5)%7+1 <= 0 AND (DAYOFWEEK('".$fine."')+5)%7+1 > 0) OR ((DAYOFWEEK('".$fine."')+5)%7+1 < (DAYOFWEEK('".$inizio."')+5)%7+1 AND (DAYOFWEEK('".$fine."')+5)%7+1 <= 0)
                           OR DATEDIFF('".$fine."','".$inizio."')>7
                      THEN POWER(10, 6) ELSE 0 END
           + CASE WHEN ((DAYOFWEEK('".$inizio."')+5)%7+1 <= 1 AND (DAYOFWEEK('".$fine."')+5)%7+1 > 1) OR ((DAYOFWEEK('".$fine."')+5)%7+1 < (DAYOFWEEK('".$inizio."')+5)%7+1 AND (DAYOFWEEK('".$fine."')+5)%7+1 <= 1)
                           OR DATEDIFF('".$fine."','".$inizio."')>7
                      THEN POWER(10, 5) ELSE 0 END
           + CASE WHEN ((DAYOFWEEK('".$inizio."')+5)%7+1 <= 2 AND (DAYOFWEEK('".$fine."')+5)%7+1 > 2) OR ((DAYOFWEEK('".$fine."')+5)%7+1 < (DAYOFWEEK('".$inizio."')+5)%7+1 AND (DAYOFWEEK('".$fine."')+5)%7+1 <= 2)
                           OR DATEDIFF('".$fine."','".$inizio."')>7
                      THEN POWER(10, 4) ELSE 0 END
           + CASE WHEN ((DAYOFWEEK('".$inizio."')+5)%7+1 <= 3 AND (DAYOFWEEK('".$fine."')+5)%7+1 > 3) OR ((DAYOFWEEK('".$fine."')+5)%7+1 < (DAYOFWEEK('".$inizio."')+5)%7+1 AND (DAYOFWEEK('".$fine."')+5)%7+1 <= 3)
                           OR DATEDIFF('".$fine."','".$inizio."')>7
                      THEN POWER(10, 3) ELSE 0 END
           + CASE WHEN ((DAYOFWEEK('".$inizio."')+5)%7+1 <= 4 AND (DAYOFWEEK('".$fine."')+5)%7+1 > 4) OR ((DAYOFWEEK('".$fine."')+5)%7+1 < (DAYOFWEEK('".$inizio."')+5)%7+1 AND (DAYOFWEEK('".$fine."')+5)%7+1 <= 4)
                           OR DATEDIFF('".$fine."','".$inizio."')>7
                      THEN POWER(10, 2) ELSE 0 END
           + CASE WHEN ((DAYOFWEEK('".$inizio."')+5)%7+1 <= 5 AND (DAYOFWEEK('".$fine."')+5)%7+1 > 5) OR ((DAYOFWEEK('".$fine."')+5)%7+1 < (DAYOFWEEK('".$inizio."')+5)%7+1 AND (DAYOFWEEK('".$fine."')+5)%7+1 <= 5)
                           OR DATEDIFF('".$fine."','".$inizio."')>7
                      THEN POWER(10, 1) ELSE 0 END
           + CASE WHEN ((DAYOFWEEK('".$inizio."')+5)%7+1 <= 6 AND (DAYOFWEEK('".$fine."')+5)%7+1 > 6) OR ((DAYOFWEEK('".$fine."')+5)%7+1 < (DAYOFWEEK('".$inizio."')+5)%7+1 AND (DAYOFWEEK('".$fine."')+5)%7+1 <= 6)
                           OR DATEDIFF('".$fine."','".$inizio."')>7
                      THEN POWER(10, 0) ELSE 0 END
             & BINARY CASE WHEN '".$ricorsivo."' = '0000000' THEN '1111111' ELSE '".$ricorsivo."' END > 0
			AND BINARY 
			   CASE WHEN ((DAYOFWEEK(inizio)+5)%7+1 <= 0 AND (DAYOFWEEK(fine)+5)%7+1 > 0) OR ((DAYOFWEEK(fine)+5)%7+1 < (DAYOFWEEK(inizio)+5)%7+1 AND (DAYOFWEEK(fine)+5)%7+1 <= 0)
							 OR DATEDIFF(fine,inizio)>7
						THEN POWER(10, 6) ELSE 0 END
			 + CASE WHEN ((DAYOFWEEK(inizio)+5)%7+1 <= 1 AND (DAYOFWEEK(fine)+5)%7+1 > 1) OR ((DAYOFWEEK(fine)+5)%7+1 < (DAYOFWEEK(inizio)+5)%7+1 AND (DAYOFWEEK(fine)+5)%7+1 <= 1)
							 OR DATEDIFF(fine,inizio)>7
						THEN POWER(10, 5) ELSE 0 END
			 + CASE WHEN ((DAYOFWEEK(inizio)+5)%7+1 <= 2 AND (DAYOFWEEK(fine)+5)%7+1 > 2) OR ((DAYOFWEEK(fine)+5)%7+1 < (DAYOFWEEK(inizio)+5)%7+1 AND (DAYOFWEEK(fine)+5)%7+1 <= 2)
							 OR DATEDIFF(fine,inizio)>7
						THEN POWER(10, 4) ELSE 0 END
			 + CASE WHEN ((DAYOFWEEK(inizio)+5)%7+1 <= 3 AND (DAYOFWEEK(fine)+5)%7+1 > 3) OR ((DAYOFWEEK(fine)+5)%7+1 < (DAYOFWEEK(inizio)+5)%7+1 AND (DAYOFWEEK(fine)+5)%7+1 <= 3)
							 OR DATEDIFF(fine,inizio)>7
						THEN POWER(10, 3) ELSE 0 END
			 + CASE WHEN ((DAYOFWEEK(inizio)+5)%7+1 <= 4 AND (DAYOFWEEK(fine)+5)%7+1 > 4) OR ((DAYOFWEEK(fine)+5)%7+1 < (DAYOFWEEK(inizio)+5)%7+1 AND (DAYOFWEEK(fine)+5)%7+1 <= 4)
							 OR DATEDIFF(fine,inizio)>7
						THEN POWER(10, 2) ELSE 0 END
			 + CASE WHEN ((DAYOFWEEK(inizio)+5)%7+1 <= 5 AND (DAYOFWEEK(fine)+5)%7+1 > 5) OR ((DAYOFWEEK(fine)+5)%7+1 < (DAYOFWEEK(inizio)+5)%7+1 AND (DAYOFWEEK(fine)+5)%7+1 <= 5)
							 OR DATEDIFF(fine,inizio)>7
						THEN POWER(10, 1) ELSE 0 END
			 + CASE WHEN ((DAYOFWEEK(inizio)+5)%7+1 <= 6 AND (DAYOFWEEK(fine)+5)%7+1 > 6) OR ((DAYOFWEEK(fine)+5)%7+1 < (DAYOFWEEK(inizio)+5)%7+1 AND (DAYOFWEEK(fine)+5)%7+1 <= 6)
							 OR DATEDIFF(fine,inizio)>7
						THEN POWER(10, 0) ELSE 0 END
			   & BINARY CASE WHEN ricorsivo = '0000000' THEN '1111111' ELSE ricorsivo END > 0
        ");
        
        
    }
    
    function aggiungiCalendarioEsterno()
    {
        global $db;
        $db->query("INSERT INTO calendari_esterni(id) VALUES(NULL)");
        $this->id=$db->insert_id;
        return $this->id;
    }
    
    function rimuoviCalendarioEsterno($id)
    {
        global $db;
        $db->query("DELETE FROM calendario WHERE id_calendario_esterno=$id");
        return $db->query("DELETE FROM calendari_esterni WHERE id=$id");
    }
    
    function getElencoCalendariEsterni($username=null)
    {
        global $db;
        if(!$username)
            $username=getMyUsername();
        
        return    $db->get_results("SELECT * FROM calendari_esterni WHERE username='$username'");
    }
    
    function getCalendarioEsterno($id)
    {
        global $db;
      
        return    $db->get_row("SELECT * FROM calendari_esterni WHERE id='$id'");
    }
    
    function salvaCalendarioEsterno($values)
    {
        global $db;
        $query="";
        foreach($values as $key=>$val)
            $query.=$key."='".$val."',";
            
        $query=rtrim($query,",");
        return $db->query("UPDATE calendari_esterni SET $query WHERE id=".$values['id']);
    }
    
    
    function calendarioICS($username,$interni="") {
        
        
        global $db; // uso il database
        // Inizio corpo della funzione //////
        
        require_once CLASS_PATH.'progetto.class.php';
        
        
        if($interni=="interni")
            $interni=" AND id_calendario_esterno IS NULL ";
        else
            $interni="";
        
            
        
        $eventi=$db->get_results("SELECT * FROM calendario WHERE username='$username' AND inizio>DATE_SUB(NOW(), INTERVAL 3 MONTH) $interni ORDER BY inizio");
        
        
        $out= "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:-//appsmm//EN\r\nCALSCALE:GREGORIAN\r\nREFRESH-INTERVAL;VALUE=DURATION:PT2H\r\nX-PUBLISHED-TTL:PT2H\r\nTIMEZONE-ID:Europe/Rome\r\nX-WR-TIMEZONE:Europe/London\r\n";
        
        
        for($i=0;$i<count($eventi);$i++)
        {
            $e=$eventi[$i];
            
            $progetto=new Progetto(Array(
                "id"=>$e->id_progetto
                ));
           
            
            
            $inizio = new DateTime($e->inizio, new DateTimeZone('Europe/Rome'));
            $inizio->setTimezone(new DateTimeZone('UTC'));
            $fine = new DateTime($e->fine, new DateTimeZone('Europe/Rome'));
            $fine->setTimezone(new DateTimeZone('UTC'));
            $ora = new DateTime(date("Y-m-d H:i:s"), new DateTimeZone('Europe/Rome'));
            $ora->setTimezone(new DateTimeZone('UTC'));
            $modificato = new DateTime($e->modificato, new DateTimeZone('Europe/Rome'));
            $modificato->setTimezone(new DateTimeZone('UTC'));
            
            if($progetto->getNome())
                $titolo=$progetto->getNome()." - ".$e->titolo;
            else
                $titolo=$e->titolo;
            
            
                $out.= "BEGIN:VEVENT\r\nDESCRIPTION:". html_entity_decode($e->descrizione, ENT_QUOTES , 'UTF-8')."\r\nSUMMARY:".html_entity_decode($titolo, ENT_QUOTES , 'UTF-8')."\r\nDTSTART:".$inizio->format('Ymd\THis\Z')."\r\nDTEND:". $fine->format('Ymd\THis\Z')."\r\nDTSTAMP:". $ora->format('Ymd\THis\Z')."\r\nLAST-MODIFIED:". $modificato->format('Ymd\THis\Z')."\r\nUID:".$e->id."\r\nEND:VEVENT\r\n";
            
        }
        
        $out.= "END:VCALENDAR";
        
        return $out;
    }
    
    
    function generaTokenEsportazione($username)
    {
        global $db;
        $salt=$db->get_var("SELECT salt FROM calendario_esportazioni WHERE username='$username'");
        
        if(!$salt)
        {
            $db->query("INSERT INTO calendario_esportazioni(salt,username) VALUES(1,'$username')");
            $salt=1;
        }
        
        return sha1($username.SECURITY_SALT.$salt);
    }

    function esportaCalendari($calendari = "", $utenti = "", $data_inizio, $data_fine){
        global $db;
        
        $filtro="";
        if($calendari<>"")
        {
            $in_calendario=implode(",", $calendari);
            $filtro.=" AND c.id_calendario IN ($in_calendario)";
        }
        if($utenti<>"")
        {
            $in_user=implode("','", $utenti);
            $filtro.=" AND c.username IN ('$in_user')";
        }
        
        return $db->get_results("SELECT inizio, fine, nome, titolo, c.username FROM calendario c, calendario_calendari cc WHERE c.id_calendario=cc.id $filtro AND inizio >= '$data_inizio' AND fine <= '$data_fine 23:59:59'");
    }
    
}

?>