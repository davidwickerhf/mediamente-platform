<?php

declare(strict_types=1);
require_once ROOT_PATH . 'classes/macchina.class.php';
require_once ROOT_PATH . 'classes/prenotazione.class.php';
require_once ROOT_PATH . 'classes/manutenzione.class.php';
require_once ROOT_PATH . 'libraries/Database.php';
require_once ROOT_PATH . 'classes/log.class.php';


/**
 * Model class for easy access to the database tables
 *  `macchine`, `prenotazioni` and `manutenzioni`.
 * PHP Version 7.4.
 *
 * @author    David Henry Francis Wicker (https://github.com/davidwickerhf) <davidwickerhf@gmail.com>
 * @license   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */
class Macchina
{
    private Database $db;
    private Log $logger;

    public function __construct()
    {
        $this->db = new Database;
        $this->logger = new Log(array("controller" => "macchina", "action" => 'database'), $this->db);
    }

    // SECTION: UTILITIES

    /**
     * Generate unique id for the database.
     * 
     * @return string UUID.
     */
    private function generateUUID()
    {
        $this->db->query('SELECT UUID_SHORT()');
        $result = $this->db->single();
        return json_decode(json_encode($result), true)['UUID_SHORT()'];
    }

    /**
     * Convert data returned from DB into the according class
     * 
     * @param string class Class Name to convert the oject to.
     * @param mixed object Object returned from DB.
     * @return object Class Object.
     * @throws PDOException if binding values to parameters fails.
     */
    private function convert(string $class, $object): object
    {
        $parameters = json_decode(json_encode($object), true);
        $object = new $class($parameters);
        return $object;
    }

    // SECTION: Methods relative to database queries, table 'macchine'

    /**
     * Retrieve a car from the DB by its id.
     * 
     * @param string id Id of the car.
     * @return ?CMacchina Returns null if the row doesn't exist.
     * @throws PDOException if binding values to parameters fails.
     */
    public function getCar(string $id): ?CMacchina
    {
        // Retrieve row
        $this->db->query('SELECT * FROM macchine WHERE id = :id');
        $this->db->bind(':id', $id);
        $result = $this->db->single();

        // Catch errors
        if (is_null($result)) {
            return null;
        }

        // Return query in CMacchina object
        return $this->convert(CMacchina::class, $result);
    }

    /**
     * Retrieve all cars from DB
     * 
     * @return ?array Array of `CMacchina` objects.
     *  Returns empty array if no cars are found.
     *  Null is returned if the query fails.
     * @throws PDOException if binding values to parameters fails.
     */
    public function getAllCars(): ?array
    {
        // Retrieve rows
        $this->db->query('SELECT * FROM macchine');
        $result = $this->db->resultSet();

        // Catch errors
        if (is_null($result)) {
            return null;
        }
        // Convert to CMacchina
        $cars = array();
        foreach ($result as $object) {
            array_push($cars, $this->convert(CMacchina::class, $object));
        }
        return $cars;
    }

    /**
     * Retrieve `count` number of cars.
     * 
     * @param int count Number of cars to retrieve.
     *  Must be greater than 0;
     * @return ?array Array of `CMacchina` objects.
     *  Returns empty array if no cars are found.
     *  Null is returned if the query fails.
     * @throws PDOException if binding values to parameters fails.
     */
    public function getCars(int $count): ?array
    {
        // Retrieve rows
        $this->db->query('SELECT * FROM macchine LIMIT :count');
        $this->db->bind(':count', $count);
        $result = $this->db->resultSet();

        // Catch errors
        if (is_null($result)) {
            return null;
        }

        // Convert to CMacchina
        $cars = array();
        foreach ($result as $object) {
            array_push($cars, $this->convert(CMacchina::class, $object));
        }
        return $cars;
    }

    /**
     * Retrieve cars of a certain location.
     * 
     * @param string sede Location to query.
     * @return ?array Array of `CMacchina` objects.
     *  Returns empty array if no cars are found.
     *  Null is returned if the query fails.
     * @throws PDOException if binding values to parameters fails.
     */
    public function getCarsBySede(string $sede): ?array
    {
        // Retrieve rows
        $this->db->query('SELECT * FROM macchine WHERE sede = :sede');
        $this->db->bind(':sede', $sede);
        $result = $this->db->resultSet();

        // Catch errors
        if (is_null($result)) {
            return null;
        }

        // Convert to CMacchina
        $cars = array();
        foreach ($result as $object) {
            array_push($cars, $this->convert(CMacchina::class, $object));
        }
        return $cars;
    }

    // SECTION: Methods relative to database queries, table 'prenotazioni'

    // TODO: implement getReservations method
    public function getReservations(int $count): ?array
    {
        // Retrieve row
        $this->db->query('SELECT * FROM macchine WHERE id = :id');
        $this->db->bind(':id', $id);
        $result = $this->db->single();

        // Catch errors
        if (is_null($result)) {
            return null;
        }

        // Return query in CMacchina object
        return $this->convert(CMacchina::class, $result);
    }

    // TODO: fix getUserReservations method
    /**
     * Get a user's reservations.
     * 
     * @param string username state of the prenotazioni dropdown.
     * @param ?int count Number of entries to retrieve. 
     *  Defaults to `0`, which will retrieve all entries.
     * @return array array containing `Prenotazione` objects
     * @see /classes/prenotazione.class.php
     */
    public function getReservationsByUser(string $username, ?int $count = 0): ?array
    {
        // Prepare statement
        $stmt = 'SELECT * 
            FROM prenotazioni
            WHERE username = :username 
            ORDER BY prenotazioni.created_at DESC';
        if ($count > 0) {
            $stmt = $stmt
                . ' LIMIT :count';
        }
        $this->db->query($stmt);
        $this->db->bind(':username', $username);
        if ($count > 0) {
            $this->db->bind(':count', $count);
        };
        // Get results
        $results = $this->db->resultSet();
        // Handle error
        if (is_null($results)) {
            return null;
        }
        // Map results to array of Prenotazioni objects
        $prenotazioni = array();
        foreach ($results as $temp) {
            array_push($prenotazioni, new CPrenotazione($temp));
        }
        return $prenotazioni;
    }

    // TODO: implement getUserOngoingReservations method
    public function getUserOngoingReservations(string $username): CPrenotazione
    {
        //$this->db->query();
        //$this->db->
        return new CPrenotazione();
    }


    // SECTION: Methods relative to database queries, table 'manutenzioni'

    // TODO: implement getManutenzione method

    // TODO: implement getCarManutenzioni method

    // TODO: implement getCarLastManutenzione method

    // SECTION: Methods relative to the management of cars

    /**
     * Register car into db.
     * 
     * @param string username Username of the user registering the car.
     * @param string marca Brand of the car being registered.
     * @param string modello Model/Name of the car.
     * @param string sede Location to register the car in. 
     *  Can be: `torino`, `milano`, `bologna`, `empoli`.
     * @param ?string commento Any comment, ideally a description of the car.
     * @return ?CMacchina object representation of the registered car.
     * @throws PDOException if binding values to parameters fails.
     */
    public function register(string $username, string $marca, string $modello, string $sede, ?string $commento = null): ?CMacchina
    {
        // Generate UUID
        $uuid = $this->generateUUID();

        // Query statement
        $stmt = 'INSERT INTO macchine (id, username, marca, modello, sede, commento, created_at, data_archivazione, disponibile, archiviata) VALUES (:uuid, :username, :marca, :modello, :sede, :commento, DEFAULT, DEFAULT, DEFAULT, DEFAULT)';
        $this->db->query($stmt);

        // Bind values
        $this->db->bind(':uuid', $uuid);
        $this->db->bind(':username', $username);
        $this->db->bind(':marca', $marca);
        $this->db->bind(':modello', $modello);
        $this->db->bind(':sede', $sede);
        $this->db->bind(':commento', $commento);

        // Execute
        if ($this->db->execute()) {
            // Retrieve added row
            return $this->getCar($uuid);
        } else {
            // Adding failed
            return null;
        }
    }

    // TODO: implement archive method
    /**
     * Archive a car
     * 
     * @param string username User registering the car.
     * @param string id UUID of the car to archive.
     * @return ?CMacchina object representation of the updated car.
     *  Null is returned if function failed.
     * @throws PDOException if binding values to parameters fails.
     */
    public function archive(string $username, string $id): ?CMacchina
    {
        // Create Statement
        $stmt = 'UPDATE macchine 
                    SET archiviata = 1, archiviata_da = :username
                    WHERE id = :id';
        // Run query
        $this->db->query($stmt);
        $this->db->bind(':username', $username);
        $this->db->bind(':id', $id);
        // Catch error
        if (!$this->db->execute()) {
            return null;
        }
        // Return updated car
        return $this->getCar($id);
    }

    // TODO: implement unarchive method
    /**
     * Archive a car
     * 
     * @param string username User registering the car.
     * @param string id UUID of the car to archive.
     * @return ?CMacchina object representation of the updated car.
     *  Null is returned if function failed
     * @throws PDOException if binding values to parameters fails.
     */
    public function unarchive(string $id): ?CMacchina
    {
        // Create Statement
        $stmt = 'UPDATE macchine 
                    SET archiviata = 0, archiviata_da = DEFAULT
                    WHERE id = :id';
        // Run query
        $this->db->query($stmt);
        $this->db->bind(':id', $id);
        // Catch error
        if (!$this->db->execute()) {
            return null;
        }
        // Return updated car
        return $this->getCar($id);
    }

    // TODO: implement delete method
    /**
     * Delete a car
     * 
     * @param string id UUID of the car to archive.
     * @return bool Status of the request.
     * @throws PDOException if binding values to parameters fails.
     */
    public function delete(string $id): bool
    {
        // Create Statement
        $stmt = 'DELETE FROM macchine WHERE id = :id';
        // Run query
        $this->db->query($stmt);
        $this->db->bind(':id', $id);
        // Catch error
        if (!$this->db->execute()) {
            return false;
        }


        if ($this->db->rowCount() > 0) {
            return true;
        }
        return false;
    }

    // SECTION: Methods relative to the reservation of cars

    // TODO: implement reserve method
    public function reserve(): ?CPrenotazione
    {
        throw new Exception('Not implemented');
    }

    // TODO: implement editReservation method
    public function editReservation(): ?CPrenotazione
    {
        throw new Exception('Not implemented');
    }

    // TODO: implement cancelReservation method
    public function cancelReservation(): bool
    {
        throw new Exception('Not implemented');
    }

    // SECTION: Methods relative to car maintenance
    // TODO: implement manutenzione method
    public function manutenzione(): CManutenzione
    {
        throw new Exception('Not implemented');
    }

    // TODO: implement editManutenzione method
    public function editManutenzione(): CManutenzione
    {
        throw new Exception('Not implemented');
    }

    // TODO: implement deleteManutenzione method
    public function deleteManutenzione(): bool
    {
        throw new Exception('Not implemented');
    }
}
