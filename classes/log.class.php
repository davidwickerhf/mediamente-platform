<?php

if (!function_exists('getMyUsername')) {
    require_once ROOT_PATH . 'commonfunctions.php';
}

if (!class_exists('Database')) {
    require_once ROOT_PATH . 'libraries/Database.php';
}

/**
 * Logger class
 * PHP Version 7.4.
 *
 * @author    Saverio Leoni
 * @author    David Henry Francis Wicker (https://github.com/davidwickerhf) <davidwickerhf@gmail.com>
 * @license   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */
class Log
{
    private Database $database;
    private bool $testing;

    /**
     * Create Logger instance
     * 
     * @param array array Array containing
     *  `controller` and `action`.
     * @param Database db Database interface to log.
     */
    public function __construct($array, Database $db = null, bool $test = false)
    {
        $this->testing = $test;

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
        $stmt = "INSERT INTO log (username, controller, action, parameters) VALUES ( :username, :controller, :action, :parameters)";
        $this->database->query($stmt);
        if ($this->testing) {
            $username = 'testing';
        } else {
            $username = getMyUsername();
        }
        $this->database->bind(':username', $username);
        $this->database->bind(':controller', $this->controller);
        $this->database->bind(':action', $this->action);
        $this->database->bind(':parameters', $what);

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