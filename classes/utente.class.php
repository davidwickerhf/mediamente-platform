<?php

class Utente {
 
    public function __construct($array=null) {
        
        global $db;
        
        $this->username = $array['username'] ? $array['username'] : null;
        
    }
    
    function getElencoUtenti($filtro="")
    {
        global $db;
        return $db->get_results("SELECT * FROM utenti WHERE enabled=1 OR external=1 $filtro ORDER BY cognome,nome");
    }
    
    function isEnabled()
    {
        if(!$this->username)
            return false;
        
        global $db;
        
        if($db->get_var("SELECT COUNT(*) FROM utenti WHERE username='".$this->username."' AND (enabled=1 OR external=1)"))
            return true;
        
        return false;
    }
    
    
    function ldapSync()
    {
        global $db;
        
        $adServer = "LDAP://".LDAP_SERVER;
        
        $ldap = ldap_connect($adServer);
        $username =LDAP_USER;
        $password = LDAP_PASSWORD;
       
        $ldaprdn = LDAP_DOMAIN . "\\" . $username;
        
        ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
        
        $bind = @ldap_bind($ldap, $ldaprdn, $password);
        
        
        if ($bind) {
            $result = ldap_search($ldap,LDAP_BASE,"(&(objectCategory=person)(samaccountname=*))");
            $info = ldap_get_entries($ldap, $result);
            
            
            $db->query("UPDATE utenti SET enabled=0");
           
            
            foreach($info as $user)
            {
                if(!isset($user["memberof"]))
                    continue;
                
                if(    !in_array("CN=GroupMMI,CN=Users,DC=mmonline,DC=local", $user["memberof"])
                    && !in_array("CN=GroupMMBI,CN=Users,DC=mmonline,DC=local", $user["memberof"])
                    && !in_array("CN=GroupMMK,CN=Users,DC=mmonline,DC=local", $user["memberof"])
                    && !in_array("CN=GroupMM,CN=Users,DC=mmonline,DC=local", $user["memberof"])
                )
                    $isAccountDisabled=true;
                else
                    $isAccountDisabled = ($user["useraccountcontrol"][0] & 2) == 2;
                
               
                $db->query("INSERT INTO utenti(username,nome,cognome,enabled) VALUES(
                        '".$user["samaccountname"][0]."',
                        '".addslashes($user["givenname"][0])."',
                        '".addslashes($user["sn"][0])."',
                        '".($isAccountDisabled ? 0 : 1 )."'
                    )
                    ON DUPLICATE KEY UPDATE nome=VALUES(nome),cognome=VALUES(cognome),enabled=VALUES(enabled)");
                
               
                
            }
                
               
                
        } 
        @ldap_close($ldap);
    }
}

?>