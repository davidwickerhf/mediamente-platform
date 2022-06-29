<?php

class Log {

    
    public function __construct($array) {
        
        global $db;
        
        $this->controller= $array['controller'] ?  $array['controller'] : null;
        $this->action= $array['action'] ?  $array['action'] : null;
      
           
        
    }
  
    function log($what)
    {
        global $db;
        return $db->query("INSERT INTO log(username,controller,action,parameters)
                VALUES('".getMyUsername()."','".$this->controller."','".$this->action."','".$what."')");
    }
    

}

?>