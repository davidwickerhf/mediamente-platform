<?php

if (!function_exists('getMyUsername')) {
    require_once ROOT_PATH . 'libraries/commonfunctions.php';
}

if (!class_exists('Database')) {
    require_once ROOT_PATH . 'libraries/Database.php';
}


class Log
{

    private Database $database;

    /**
     * Create Logger instance
     * 
     * @param array array Array containing
     *  `controller` and `action`.
     * @param Database db Database interface to log.
     */
    public function __construct($array, Database $db = null)
    {

        if (is_null($db)) {
            $this->database = new Database;
        } else {
            $this->database = $db;
        }

        $this->controller = $array['controller'] ?  $array['controller'] : null;
        $this->action = $array['action'] ?  $array['action'] : null;
    }

    /**
     * Log message onto database
     * 
     * @param string what message to log
     */
    function log($what)
    {
        $stmt = "INSERT INTO log(username,controller,action,parameters)
                VALUES('" . getMyUsername() . "','" . $this->controller . "','" . $this->action . "','" . $what . "')";
        $this->database->query($stmt);
        $this->database->execute();
        // Execute
        if ($this->database->execute()) {
            // Retrieve added row
            return true;
        } else {
            // Adding failed
            return false;
        }
    }
}