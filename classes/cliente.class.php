<?php

class Cliente
{

    public function __construct($array = null)
    {
        global $db;
        
        $this->id= $array['id'] ?  $array['id'] : null;
        
        $this->caricaCliente();
    }

    function caricaCliente()
    {
        global $db;
        if(!$this->id)
            return;
            $cliente=$db->get_row("SELECT * FROM clienti WHERE codice_cliente='".$this->id."'");
        $this->cliente=$cliente;
    }
    
    
    function aggiornaCommesse()
    {
        global $db;
        global $orcldb;
        
        $commesse = $orcldb->get_results("select
                s.SOTTOCM_DSC
                ,s.SOTTOC_ID
                ,s.SOTTOC_COD
                ,c.CLI_COD
                ,c.COM_ID
                ,c.COM_ANNO
                ,c.COM_NUMERO
                ,c.COM_DESC
                ,c.CLI_DES
                ,c.CDC_DESC
                ,c.DATA_APERTURA_COMMESSA
                ,c.DATA_CHIUSURA
                ,c.DATA_INIZIO_CONTRATTO
                ,c.DATA_FINE_CONTRATTO
                ,s.PREVISTO_DA
                ,s.PREVISTO_A
                ,s.AGGIORNATO_DA
                ,s.AGGIORNATO_A
                ,s.EFFETTIVO_DA
                ,s.EFFETTIVO_A
                ,c.TIPO_C
                from (
                    select CASE when  COD_ARTICOLO  like 'AS%'  OR  COD_ARTICOLO LIKE  '%MCCLSVIMPR%' THEN 'Canone'
                    ELSE  TIPO_COMMESSA  END tipo_c, a.* from dim_commesse a) c,
                    DIM_SOTTOCOMMESSE s
                    where
                    tipo_c IS NOT NULL and
                    c.com_id=s.com_id
                ");
        
        for ($i = 0; $i < count($commesse); $i ++) {
            $comm = $commesse[$i];
            
            $data_inizio_contratto = null;
            $data_fine_contratto = null;
            if ($comm->EFFETTIVO_DA)
                $data_inizio_contratto = DateTime::createFromFormat("d-M-y", $comm->EFFETTIVO_DA);
            elseif ($comm->AGGIORNATO_DA)
                $data_inizio_contratto = DateTime::createFromFormat("d-M-y", $comm->AGGIORNATO_DA);
            elseif ($comm->PREVISTO_DA)
                $data_inizio_contratto = DateTime::createFromFormat("d-M-y", $comm->PREVISTO_DA);
            
            if ($data_inizio_contratto)
                $data_inizio_contratto = "'" . $data_inizio_contratto->format('Y-m-d 00:00:00') . "'";
            else
                $data_inizio_contratto = "'2001/01/01 00:00:00'";
            
            if ($comm->EFFETTIVO_A)
                $data_fine_contratto = DateTime::createFromFormat("d-M-y", $comm->EFFETTIVO_A);
            elseif ($comm->AGGIORNATO_A)
                $data_fine_contratto = DateTime::createFromFormat("d-M-y", $comm->AGGIORNATO_A);
            elseif ($comm->PREVISTO_A)
                $data_fine_contratto = DateTime::createFromFormat("d-M-y", $comm->PREVISTO_A);
            
            if ($data_fine_contratto)
                $data_fine_contratto = "'" . $data_fine_contratto->format('Y-m-d 23:59:59') . "'";
            else
                $data_fine_contratto = "'2001/01/01 00:00:00'";
            
            $commnum=$comm->SOTTOC_ID;
            $commnum=substr($commnum, 0,4)."/".substr($commnum, 5,3)."/".substr($commnum, 8,2);
            
            $insquery = "INSERT INTO commesse(id,nome,codice_cliente,created,updated,valid_from,valid_to,tipo)
                        VALUES(" . $comm->SOTTOC_ID . ",
                        '" .$commnum." ". addslashes(str_replace("?", " ", $comm->SOTTOCM_DSC) . ' // ' . str_replace("?", " ", $comm->COM_DESC)) . "',
                        '" . $comm->CLI_COD . "',
                        NOW(),
                        NOW(),
                        STR_TO_DATE(" . $data_inizio_contratto . ",'%Y-%m-%d %H:%i:%s'),
                        STR_TO_DATE(" . $data_fine_contratto . ",'%Y-%m-%d %H:%i:%s'),
                        '" . $comm->TIPO_C . "')
                        ON DUPLICATE KEY UPDATE
                        nome=VALUES(nome),
                        updated=NOW(),
                        valid_from=VALUES(valid_from),
                        valid_to=VALUES(valid_to),
                        tipo=VALUES(tipo)
                           ";
            
            $db->query($insquery);
            
            $insquery = "INSERT INTO clienti(codice_cliente,nome)
                        VALUES('" . $comm->CLI_COD . "',
                        '" . addslashes($comm->CLI_DES) . "'
                       )
                        ON DUPLICATE KEY UPDATE nome=VALUES(nome)
                           ";
            $db->query($insquery);
        }
    }
    
    function getElencoClienti($filtro)
    {
        global $db;
        return $db->get_results("SELECT * FROM clienti WHERE 1=1 $filtro ORDER BY nome");
    }
    
    function getProgetti($filtro)
    {
        global $db;
        return $db->get_results("SELECT * FROM progetti WHERE 1=1 $filtro AND codice_cliente='".$this->id."'");
    }
    
    function getProgettiCalendario($id_calendario,$filtro=null)
    {
        global $db;
        return $this->getProgetti("AND id_calendario=$id_calendario $filtro");
    }
    
    function getCliente()
    {
        global $db;
        return $this->cliente;
    }
    
    function getCommesse($filtro)
    {
        global $db;
        
        return $db->get_results("SELECT * FROM commesse WHERE 1=1 $filtro AND codice_cliente='".$this->id."' ORDER BY nome");
    }
    
    function getCommessa($id)
    {
        global $db;
        
        return $db->get_row("SELECT * FROM commesse WHERE id=$id AND codice_cliente='".$this->id."'");
    }
    
    
}

?>