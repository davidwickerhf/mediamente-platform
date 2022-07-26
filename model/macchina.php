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
        $this->logger = new Log(array("controller" => "macchina", "action" => 'database'));
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

    /**
     * Check if a username exists in the table `utenti`.
     * 
     * @param string username User to query.
     * @return bool Result.
     * @throws PDOException if binding values to parameters fails.
     */
    private function checkUsername(string $username): bool
    {
        $stmt = 'SELECT * FROM utenti WHERE username = :username';
        $this->db->query($stmt);
        $this->db->bind(':username', $username);
        $result = $this->db->single();
        if (is_null($result)) {
            return false;
        }
        return true;
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

    /**
     * Retrieve a reservation from the DB by its id.
     * 
     * @param string id Id of the reservation.
     * @return ?CPrenotazione Returns null if the row doesn't exist.
     * @throws PDOException if binding values to parameters fails.
     */
    public function getReservation(string $id): ?CPrenotazione
    {
        // Retrieve row
        $this->db->query('SELECT * FROM prenotazioni WHERE id = :id');
        $this->db->bind(':id', $id);
        $result = $this->db->single();

        // Catch errors
        if (is_null($result)) {
            return null;
        }

        // Return query in CPrenotazione object
        return $this->convert(CPrenotazione::class, $result);
    }

    /**
     * Get a user's reservations.
     * 
     * @param string User to query.
     * @return ?array array containing `Prenotazione` objects
     * @throws PDOException if binding values to parameters fails.
     */
    public function getAllUserReservations(string $username): ?array
    {
        // Check utente
        if (!$this->checkUsername($username)) {
            return null;
        }

        // Prepare statement
        $stmt = 'SELECT * 
            FROM prenotazioni
            JOIN utenti ON prenotazioni.username = utenti.username
            WHERE utenti.username = :username 
            ORDER BY prenotazioni.from_date DESC';

        $this->db->query($stmt);
        $this->db->bind(':username', $username);
        // Get results
        $results = $this->db->resultSet();
        // Handle error
        if (is_null($results)) {
            return null;
        }
        // Map results to array of Prenotazioni objects
        $prenotazioni = array();
        foreach ($results as $temp) {
            array_push($prenotazioni, $this->convert(CPrenotazione::class, $temp));
        }
        return $prenotazioni;
    }

    /**
     * Get a user's reservations.
     * 
     * @param string User to query.
     * @param int count Number of entries to retrieve. 
     *  `count` must be greater than 0.
     * @return ?array array containing `Prenotazione` objects
     * @throws PDOException if binding values to parameters fails.
     */
    public function getUserReservations(string $username, int $count): ?array
    {
        // Check utente
        if (!$this->checkUsername($username)) {
            return null;
        }

        // Prepare statement
        $stmt = 'SELECT * 
            FROM prenotazioni
            WHERE username = :username 
            ORDER BY prenotazioni.from_date DESC
            LIMIT :count';

        $this->db->query($stmt);
        $this->db->bind(':username', $username);
        $this->db->bind(':count', $count);
        // Get results
        $results = $this->db->resultSet();
        // Handle error
        if (is_null($results)) {
            return null;
        }
        // Map results to array of Prenotazioni objects
        $prenotazioni = array();
        foreach ($results as $temp) {
            array_push($prenotazioni, $this->convert(CPrenotazione::class, $temp));
        }
        return $prenotazioni;
    }

    /**
     * Get a user's ongoing reservation.
     * 
     * @param string User to query.
     * @return ?CPrenotazione array containing `Prenotazione` objects
     * @throws PDOException if binding values to parameters fails.
     */
    public function getUserOngoingReservation(string $username): ?CPrenotazione
    {
        // Check utente
        if (!$this->checkUsername($username)) {
            return null;
        }

        // Prepare statement
        $stmt = 'SELECT * 
            FROM prenotazioni
            WHERE username = :username
                AND CURDATE() between from_date and to_date';

        $this->db->query($stmt);
        $this->db->bind(':username', $username);
        // Get results
        $result = $this->db->single();
        // Handle error
        if (is_null($result)) {
            return null;
        }
        // Map results to array of Prenotazioni objects
        $prenotazione = $this->convert(CPrenotazione::class, $result);
        return $prenotazione;
    }

    /**
     * Get a user's future reservations.
     * 
     * @param string User to query.
     * @param int count Number of entries to retrieve. 
     *  `count` must be greater than 0.
     * @return ?array array containing `Prenotazione` objects
     * @throws PDOException if binding values to parameters fails.
     */
    public function getUserFutureReservations(string $username, int $count): ?array
    {
        // Check utente
        if (!$this->checkUsername($username)) {
            return null;
        }

        // Prepare statement
        $stmt = 'SELECT * 
            FROM prenotazioni
            WHERE username = :username 
                AND CURDATE() < prenotazioni.from_date
            ORDER BY prenotazioni.from_date ASC
            LIMIT :count';

        $this->db->query($stmt);
        $this->db->bind(':username', $username);
        $this->db->bind(':count', $count);
        // Get results
        $results = $this->db->resultSet();
        // Handle error
        if (is_null($results)) {
            return null;
        }
        // Map results to array of Prenotazioni objects
        $prenotazioni = array();
        foreach ($results as $temp) {
            array_push($prenotazioni, $this->convert(CPrenotazione::class, $temp));
        }
        return $prenotazioni;
    }


    /**
     * Get a reservations of a specific location.
     * 
     * @param string sede Location to query.
     * @param int count Number of results to return.
     * @return ?array array containing `CPrenotazione` objects.
     * @throws PDOException if binding values to parameters fails.
     */
    public function getReservationsBySede(string $sede, int $count): ?array
    {
        if (!in_array($sede, array('torino', 'milano', 'bologna', 'empoli'))) {
            return null;
        }
        // Prepare statement
        $stmt = 'SELECT * 
            FROM prenotazioni
            JOIN macchine ON prenotazioni.id_macchina = macchine.id
            WHERE macchine.sede = :sede
            LIMIT :count';

        $this->db->query($stmt);
        $this->db->bind(':sede', $sede);
        $this->db->bind(':count', $count);
        // Get results
        $results = $this->db->resultSet();
        // Handle error
        if (is_null($results)) {
            return null;
        }
        // Map results to array of Prenotazioni objects
        $prenotazioni = array();
        foreach ($results as $temp) {
            array_push($prenotazioni, $this->convert(CPrenotazione::class, $temp));
        }
        return $prenotazioni;
    }

    /**
     * Get all reservations of a specific location.
     * 
     * @param string sede Location to query.
     * @return ?array array containing `CPrenotazione` objects.
     * @throws PDOException if binding values to parameters fails.
     */
    public function getAllReservationsBySede(string $sede): ?array
    {
        if (!in_array($sede, array('torino', 'milano', 'bologna', 'empoli'))) {
            return null;
        }
        // Prepare statement
        $stmt = 'SELECT * 
            FROM prenotazioni
            JOIN macchine ON prenotazioni.id_macchina = macchine.id
            WHERE macchine.sede = :sede';

        $this->db->query($stmt);
        $this->db->bind(':sede', $sede);
        // Get results
        $results = $this->db->resultSet();
        // Handle error
        if (is_null($results)) {
            return null;
        }
        // Map results to array of Prenotazioni objects
        $prenotazioni = array();
        foreach ($results as $temp) {
            array_push($prenotazioni, $this->convert(CPrenotazione::class, $temp));
        }
        return $prenotazioni;
    }

    /**
     * Get a reservations of a specific car.
     * 
     * @param int count Number of results to return.
     * @return ?array array containing `CPrenotazione` objects.
     *  Returns null if the inserted ID is invalid.
     * @throws PDOException if binding values to parameters fails.
     */
    public function getReservationsByCar(string $id_macchina, int $count): ?array
    {
        if (is_null($this->getCar($id_macchina))) {
            return null;
        };
        // Prepare statement
        $stmt = 'SELECT * 
            FROM prenotazioni
            WHERE id_macchina = :id_macchina
            LIMIT :count';

        $this->db->query($stmt);
        $this->db->bind(':id_macchina', $id_macchina);
        $this->db->bind(':count', $count);
        // Get results
        $results = $this->db->resultSet();
        // Handle error
        if (is_null($results)) {
            return null;
        }
        // Map results to array of Prenotazioni objects
        $prenotazioni = array();
        foreach ($results as $temp) {
            array_push($prenotazioni, $this->convert(CPrenotazione::class, $temp));
        }
        return $prenotazioni;
    }

    /**
     * Get all reservations of a specific car.
     * 
     * @param string id_macchina Car to query.
     * @return ?array array containing `CPrenotazione` objects.
     *  Returns null if inserted id is invalid.
     * @throws PDOException if binding values to parameters fails.
     */
    public function getAllReservationsByCar(string $id_macchina): ?array
    {
        if (is_null($this->getCar($id_macchina))) {
            return null;
        }
        // Prepare statement
        $stmt = 'SELECT * 
            FROM prenotazioni
            WHERE id_macchina = :id_macchina';

        $this->db->query($stmt);
        $this->db->bind(':id_macchina', $id_macchina);
        // Get results
        $results = $this->db->resultSet();
        // Handle error
        if (is_null($results)) {
            return null;
        }
        // Map results to array of Prenotazioni objects
        $prenotazioni = array();
        foreach ($results as $temp) {
            array_push($prenotazioni, $this->convert(CPrenotazione::class, $temp));
        }
        return $prenotazioni;
    }

    /**
     * Get a number of reservations.
     * 
     * @param string sede Location to query.
     * @param int count Number of results to return.
     * @return ?array array containing `CPrenotazione` objects.
     * @throws PDOException if binding values to parameters fails.
     */
    public function getReservations(int $count): ?array
    {
        // Prepare statement
        $stmt = 'SELECT * 
            FROM prenotazioni
            LIMIT :count';

        $this->db->query($stmt);
        $this->db->bind(':count', $count);
        // Get results
        $results = $this->db->resultSet();
        // Handle error
        if (is_null($results)) {
            return null;
        }
        // Map results to array of Prenotazioni objects
        $prenotazioni = array();
        foreach ($results as $temp) {
            array_push($prenotazioni, $this->convert(CPrenotazione::class, $temp));
        }
        return $prenotazioni;
    }

    /**
     * Get all reservations
     * 
     * @return ?array array containing `CPrenotazione` objects.
     * @throws PDOException if binding values to parameters fails.
     */
    public function getAllReservations(): ?array
    {
        // Prepare statement
        $stmt = 'SELECT * FROM prenotazioni';

        $this->db->query($stmt);
        // Get results
        $results = $this->db->resultSet();
        // Handle error
        if (is_null($results)) {
            return null;
        }
        // Map results to array of Prenotazioni objects
        $prenotazioni = array();
        foreach ($results as $temp) {
            array_push($prenotazioni, $this->convert(CPrenotazione::class, $temp));
        }
        return $prenotazioni;
    }

    /**
     * Get all past reservations
     * 
     * @return ?array array containing `CPrenotazione` objects.
     * @throws PDOException if binding values to parameters fails.
     */
    public function getPastReservations(): ?array
    {
        // Prepare statement
        $stmt = 'SELECT * FROM prenotazioni WHERE CURDATE() > prenotazioni.to_date';

        $this->db->query($stmt);
        // Get results
        $results = $this->db->resultSet();
        // Handle error
        if (is_null($results)) {
            return null;
        }
        // Map results to array of Prenotazioni objects
        $prenotazioni = array();
        foreach ($results as $temp) {
            array_push($prenotazioni, $this->convert(CPrenotazione::class, $temp));
        }
        return $prenotazioni;
    }

    /**
     * Get all ongoing reservations
     * 
     * @return ?array array containing `CPrenotazione` objects.
     * @throws PDOException if binding values to parameters fails.
     */
    public function getOngoingReservations(): ?array
    {
        // Prepare statement
        $stmt = 'SELECT * FROM prenotazioni WHERE CAST(NOW() AS DATE) between from_date and to_date';

        $this->db->query($stmt);
        // Get results
        $results = $this->db->resultSet();
        // Handle error
        if (is_null($results)) {
            return null;
        }
        // Map results to array of Prenotazioni objects
        $prenotazioni = array();
        foreach ($results as $temp) {
            array_push($prenotazioni, $this->convert(CPrenotazione::class, $temp));
        }
        return $prenotazioni;
    }

    /**
     * Get all future reservations
     * 
     * @return ?array array containing `CPrenotazione` objects.
     * @throws PDOException if binding values to parameters fails.
     */
    public function getFutureReservations(): ?array
    {
        // Prepare statement
        $stmt = 'SELECT * FROM prenotazioni WHERE CURDATE() < from_date';

        $this->db->query($stmt);
        // Get results
        $results = $this->db->resultSet();
        // Handle error
        if (is_null($results)) {
            return null;
        }
        // Map results to array of Prenotazioni objects
        $prenotazioni = array();
        foreach ($results as $temp) {
            array_push($prenotazioni, $this->convert(CPrenotazione::class, $temp));
        }
        return $prenotazioni;
    }

    // SECTION: Methods relative to database queries, table 'manutenzioni'

    /**
     * Retrieve a maintenance from the DB by its id.
     * 
     * @param string id Id of the maintenance.
     * @return ?CManutenzione Returns null if the row doesn't exist.
     * @throws PDOException if binding values to parameters fails.
     */
    public function getManutenzione(string $id): ?CManutenzione
    {
        // Retrieve row
        $this->db->query('SELECT * FROM manutenzioni WHERE id = :id');
        $this->db->bind(':id', $id);
        $result = $this->db->single();

        // Catch errors
        if (is_null($result)) {
            return null;
        }

        // Return query in CManutenzione object
        return $this->convert(CManutenzione::class, $result);
    }

    /**
     * Retrieve a car's maintenances
     * 
     * @param string id_macchina Car to query
     * @return ?array Returns null if the query fails
     * @throws PDOException if binding values to parameters fails.
     */
    public function getCarManutenzioni(string $id_macchina): ?array
    {
        if (is_null($this->getCar($id_macchina))) {
            return null;
        }
        // Prepare statement
        $stmt = 'SELECT * 
            FROM manutenzioni
            WHERE id_macchina = :id_macchina';

        $this->db->query($stmt);
        $this->db->bind(':id_macchina', $id_macchina);
        // Get results
        $results = $this->db->resultSet();
        // Handle error
        if (is_null($results)) {
            return null;
        }
        // Map results to array of Manutenzioni objects
        $manutenzioni = array();
        foreach ($results as $temp) {
            array_push($manutenzioni, $this->convert(CManutenzione::class, $temp));
        }
        return $manutenzioni;
    }

    /**
     * Retrieve a car's last maintenance by the type
     * 
     * @param string id Id of the maintenance.
     * @param string tipologia Type of maintenance to query.
     * @return ?CManutenzione Returns null if the row doesn't exist.
     * @throws PDOException if binding values to parameters fails.
     */
    public function getCarLastManutenzione(string $id_macchina, string $tipologia): ?CManutenzione
    {
        if (is_null($this->getCar($id_macchina))) {
            return null;
        }

        // Prepare statement
        $stmt = 'SELECT * 
            FROM manutenzioni
            WHERE id_macchina = :id_macchina AND tipologia = :tipologia 
            ORDER BY manutenzioni.data DESC 
            LIMIT 1';

        $this->db->query($stmt);
        $this->db->bind(':id_macchina', $id_macchina);
        $this->db->bind(':tipologia', $tipologia);
        // Get results
        $result = $this->db->single();
        // Handle error
        if (is_null($result)) {
            return null;
        }
        // Map results to array of Manutenzioni objects
        return $this->convert(CManutenzione::class, $result);
    }

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
     * @param ?string parcheggio Parking information of the car
     * @return ?CMacchina object representation of the registered car.
     * @throws PDOException if binding values to parameters fails.
     */
    public function register(string $username, string $marca, string $modello, string $sede, ?string $commento = null, ?string $parcheggio = null): ?CMacchina
    {
        // Generate UUID
        $uuid = $this->generateUUID();

        // Query statement
        $stmt = 'INSERT INTO macchine (id, username, marca, modello, sede, commento, parcheggio, created_at, data_archivazione, archiviata) VALUES (:uuid, :username, :marca, :modello, :sede, :commento, :parcheggio, DEFAULT, DEFAULT, DEFAULT)';
        $this->db->query($stmt);

        // Bind values
        $this->db->bind(':uuid', $uuid);
        $this->db->bind(':username', $username);
        $this->db->bind(':marca', $marca);
        $this->db->bind(':modello', $modello);
        $this->db->bind(':sede', $sede);
        $this->db->bind(':commento', $commento);
        $this->db->bind(':parcheggio', $parcheggio);

        // Execute
        if ($this->db->execute()) {
            // Retrieve added row
            return $this->getCar($uuid);
        } else {
            // Adding failed
            return null;
        }
    }

    /**
     * Edit a car in the DB.
     * 
     * @param string id ID of the car to edit.
     * @param array args Associative array where the names of the 
     *  properties match the columns of the database.
     *  Allowed properties are: `marca`, `modello`, `sede`, `commento`, `parcheggio`.
     * @param string modello Model/Name of the car.
     * @return ?CMacchina object representation of the updated car.
     *  Null is returned if the query fails.
     *  Returns original car if no allowed property has been edited.
     * @throws PDOException if binding values to parameters fails.
     */
    public function edit(string $id, array $args): ?CMacchina
    {
        //Clean array from disallowed properties
        $properties = array();
        foreach ($args as $property => $value) {
            if (in_array($property, array('marca', 'modello', 'sede', 'commento', 'parcheggio'))) {
                $properties[$property] = $value;
            }
        }

        if (count($properties) > 0) {
            // Create statement
            $stmt = 'UPDATE macchine SET ';
            foreach ($properties as $property => $value) {
                $stmt = $stmt . $property . ' = :' . $property . ', ';
            }
            $stmt = rtrim($stmt, ", \t\n") . ' WHERE id = :id';
            $this->db->query($stmt);

            // Bind Values
            foreach ($properties as $property => $value) {
                $this->db->bind(':' . $property, $value);
            }
            $this->db->bind(':id', $id);

            // Execute and check for errors
            if (!$this->db->execute()) {
                return null;
            }
        }
        //Return updated car
        return $this->getCar($id);
    }

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
                    SET archiviata = 1, archiviata_da = :username, data_archivazione = CURRENT_TIMESTAMP()
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
                    SET archiviata = 0, archiviata_da = DEFAULT, data_archivazione = DEFAULT
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

    /**
     * Reserve car into db.
     * 
     * @param string id_macchina Reserved car.
     * @param string username Username who books the car.
     * @param DateTime from_date Start date of the reservation
     * @param DateTime to_date End date of the reservation.
     * @param string motivazione Can be either `aziendale` or `personale`.
     * @param ?string commento Any comment on the reservation.
     * @return ?CMacchina object representation of the registered car.
     * @throws PDOException if binding values to parameters fails.
     */
    public function reserve(string $id_macchina, string $username, DateTime $from_date, DateTime $to_date, string $motivazione, ?string $commento = null): ?CPrenotazione
    {
        // Generate UUID
        $uuid = $this->generateUUID();

        // Format dates
        $from_date = date("Y-m-d", $from_date->getTimestamp());
        $to_date = date("Y-m-d", $to_date->getTimestamp());

        // Query statement
        $stmt = 'INSERT INTO prenotazioni (id, id_macchina, username, from_date, to_date, created_at, motivazione, commento) VALUES (:uuid, :id_macchina, :username, :from_date, :to_date, DEFAULT, :motivazione, :commento)';
        $this->db->query($stmt);

        // Bind values
        $this->db->bind(':uuid', $uuid);
        $this->db->bind(':id_macchina', $id_macchina);
        $this->db->bind(':username', $username);
        $this->db->bind(':from_date', $from_date);
        $this->db->bind(':to_date', $to_date);
        $this->db->bind(':motivazione', $motivazione);
        $this->db->bind(':commento', $commento);

        // Execute
        if ($this->db->execute()) {
            // Retrieve added row
            return $this->getReservation($uuid);
        } else {
            // Adding failed
            return null;
        }
    }

    /**
     * Edit a reservation in the DB.
     * 
     * @param string id ID of the reservation to edit.
     * @param array args Associative array where the names of the 
     *  properties match the columns of the database.
     *  Allowed properties are: `from_date`, `to_date`, `motivazione`, `commento`.
     *  Disallowed or invalid properties will be ignored.
     * @param string modello Model/Name of the car.
     * @return ?CPrenotazione object representation of the updated car.
     *  Null is returned if the query fails.
     * @throws PDOException if binding values to parameters fails.
     */
    public function editReservation(string $id, array $args): ?CPrenotazione
    {
        //Clean array from disallowed properties
        $properties = array();
        foreach ($args as $property => $value) {
            if (in_array($property, array('motivazione', 'commento'))) {
                $properties[$property] = $value;
            }

            if (in_array($property, array('from_date', 'to_date'))) {
                $properties[$property] = date("Y-m-d", $value->getTimestamp());
            }
        }

        if (count($properties) > 0) {
            // Create statement
            $stmt = 'UPDATE prenotazioni SET ';
            foreach ($properties as $property => $value) {
                $stmt = $stmt . $property . ' = :' . $property . ', ';
            }
            $stmt = rtrim($stmt, ", \t\n") . ' WHERE id = :id';
            $this->db->query($stmt);

            // Bind Values
            foreach ($properties as $property => $value) {
                $this->db->bind(':' . $property, $value);
            }
            $this->db->bind(':id', $id);

            // Execute and check for errors
            if (!$this->db->execute()) {
                return null;
            }
        }
        //Return updated car
        return $this->getReservation($id);
    }

    /**
     * Cancel a reservation
     * 
     * @param string id UUID of the reservation to cancel.
     * @return bool Status of the request.
     * @throws PDOException if binding values to parameters fails.
     */
    public function cancelReservation(string $id): bool
    {
        // Create Statement
        $stmt = 'DELETE FROM prenotazioni WHERE id = :id';
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

    // SECTION: Methods relative to car maintenance

    /**
     * Reserve car into db.
     * 
     * @param string id_macchina Reserved car.
     * @param string username Username who books the car.
     * @param DateTime from_date Start date of the reservation
     * @param DateTime to_date End date of the reservation.
     * @param string motivazione Can be either `aziendale` or `personale`.
     * @param ?string commento Any comment on the reservation.
     * @return ?CMacchina object representation of the registered car.
     * @throws PDOException if binding values to parameters fails.
     */
    public function manutenzione($id_macchina, string $username, DateTime $data, string $tipologia, string $luogo, int $chilometri, string $commento): ?CManutenzione
    {
        // Check ID Macchina
        if (is_null($this->getCar($id_macchina))) {
            return null;
        }

        // Check Username
        if (!$this->checkUsername($username)) {
            return null;
        }

        // Generate UUID
        $uuid = $this->generateUUID();

        // Format dates
        $data = date("Y-m-d", $data->getTimestamp());

        // Query statement
        $stmt = 'INSERT INTO manutenzioni (id, id_macchina, username, data, created_at, tipologia, luogo, chilometri, commento) VALUES (:uuid, :id_macchina, :username, :data, DEFAULT, :tipologia, :luogo, :chilometri, :commento)';
        $this->db->query($stmt);

        // Bind values
        $this->db->bind(':uuid', $uuid);
        $this->db->bind(':id_macchina', $id_macchina);
        $this->db->bind(':username', $username);
        $this->db->bind(':data', $data);
        $this->db->bind(':tipologia', $tipologia);
        $this->db->bind(':luogo', $luogo);
        $this->db->bind(':chilometri', $chilometri);
        $this->db->bind(':commento', $commento);

        // Execute
        if ($this->db->execute()) {
            // Retrieve added row
            return $this->getManutenzione($uuid);
        } else {
            // Adding failed
            return null;
        }
    }

    /**
     * Edit a reservation in the DB.
     * 
     * @param string id ID of the reservation to edit.
     * @param array args Associative array where the names of the 
     *  properties match the columns of the database.
     *  Allowed properties are: `data`, `tipologia`, `luogo`, 
     * `chilometri`, `commento`.
     *  Disallowed or invalid properties will be ignored.
     * @param string modello Model/Name of the car.
     * @return ?CPrenotazione object representation of the updated car.
     *  Null is returned if the query fails.
     * @throws PDOException if binding values to parameters fails.
     */
    public function editManutenzione(string $id, array $args): ?CManutenzione
    {
        //Clean array from disallowed properties
        $properties = array();
        foreach ($args as $property => $value) {
            if (in_array($property, array('tipologia', 'luogo', 'chilometri', 'commento'))) {
                $properties[$property] = $value;
            }

            if (in_array($property, array('data'))) {
                $properties[$property] = date("Y-m-d", $value->getTimestamp());
            }
        }

        if (count($properties) > 0) {
            // Create statement
            $stmt = 'UPDATE manutenzioni SET ';
            foreach ($properties as $property => $value) {
                $stmt = $stmt . $property . ' = :' . $property . ', ';
            }
            $stmt = rtrim($stmt, ", \t\n") . ' WHERE id = :id';
            $this->db->query($stmt);

            // Bind Values
            foreach ($properties as $property => $value) {
                $this->db->bind(':' . $property, $value);
            }
            $this->db->bind(':id', $id);

            // Execute and check for errors
            if (!$this->db->execute()) {
                return null;
            }
        }
        //Return updated manutenzione
        return $this->getManutenzione($id);
    }

    /**
     * Delete a car
     * 
     * @param string id UUID of the manutenzione to delete.
     * @return bool Status of the request.
     * @throws PDOException if binding values to parameters fails.
     */
    public function deleteManutenzione(string $id): bool
    {
        // Create Statement
        $stmt = 'DELETE FROM manutenzioni WHERE id = :id';
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

    // SECTION: MVC UTILITY FUNCTIONS

    /**
     * MVC Function: Get number of available cars on a specific date.
     * 
     * @param DateTime date Date to query.
     * @return ?array Number of available cars.
     *  Returns null if query failed
     * @throws PDOException if binding values to parameters fails.
     */
    public function getAvailableCars(DateTime $date): ?array
    {
        // Format dates
        $date = date("Y-m-d", $date->getTimestamp());
        $stmt = 'SELECT * FROM macchine WHERE id NOT IN (SELECT id_macchina FROM prenotazioni WHERE :date between from_date and to_date)';

        $this->db->query($stmt);
        $this->db->bind(':date', $date);

        if (!$this->db->execute()) {
            return null;
        }

        $reservations = $this->db->resultSet();
        return $reservations;
    }

    /**
     * MVC Function: Get number of reserved cars on a specific date.
     * 
     * @param DateTime date Date to query.
     * @return ?array Number of available cars.
     *  Returns null if query failed
     * @throws PDOException if binding values to parameters fails.
     */
    public function getReservedCars(DateTime $date): ?array
    {
        // Format dates
        $date = date("Y-m-d", $date->getTimestamp());
        $stmt = 'SELECT * FROM macchine WHERE id IN (SELECT id_macchina FROM prenotazioni WHERE :date between from_date and to_date)';

        $this->db->query($stmt);
        $this->db->bind(':date', $date);

        if (!$this->db->execute()) {
            return null;
        }

        $reservations = $this->db->resultSet();
        return $reservations;
    }
}