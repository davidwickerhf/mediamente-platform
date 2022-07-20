<?php

declare(strict_types=1);

require_once './app.config.php';
require_once ROOT_PATH . 'tests/MemoryTestCase.php';
require_once ROOT_PATH . 'classes/prenotazione.class.php';
require_once ROOT_PATH . 'model/macchina.php';

/**
 * Unit Test class for testing Database connection for 
 *  the table: `prenotazioni`.
 * PHP Version 7.4.
 * @uses PHPUnit Version 9.
 *  Install via composer
 * @see https://phpunit.de/getting-started/phpunit-9.html
 *
 * @author    David Henry Francis Wicker (https://github.com/davidwickerhf) <davidwickerhf@gmail.com>
 * @license   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */

/**
 * @backupGlobals disabled
 */
final class PrenotazioniDBTest extends MemoryTestCase
{
    // QUERIES
    public function testGetPrenotazione(): void
    {

        //Valid ID
        $result = $this->model->getReservation($this::ID_PRENOTAZIONE);
        $this->assertInstanceOf(CPrenotazione::class, $result);

        //Get reservation invalid ID
        $this->assertNull(
            $this->model->getReservation('99060418373345288'),
            'Get reservation by invaid ID returns null'
        );
    }

    public function testGetAllUserReservations()
    {
        // Valid User ID
        $result = $this->model->getAllUserReservations($this::USERNAME_UTENTE);
        $this->assertIsArray($result);
        foreach ($result as $prenotazione) {
            $this->assertInstanceOf(CPrenotazione::class, $prenotazione);
        }

        // Invalid User ID
        $invalid = $this->model->getAllUserReservations('aisjasidahd');
        $this->assertNull($invalid);
    }

    public function testGetUserReservations(): void
    {
        // Valid User ID
        $result = $this->model->getUserReservations($this::USERNAME_UTENTE, 2);
        $this->assertIsArray($result);
        foreach ($result as $prenotazione) {
            $this->assertInstanceOf(CPrenotazione::class, $prenotazione);
        }

        // Invalid count
        $invalid = $this->model->getUserReservations($this::USERNAME_UTENTE, -1);

        $this->assertNull($invalid);

        // Invalid User ID
        $invalid = $this->model->getUserReservations('aisjasidahd', 2);
        $this->assertNull($invalid);
    }

    public function testGetUserOngoingReservation()
    {

        // Valid User ID
        $result = $this->model->getUserOngoingReservations($this::USERNAME_UTENTE);
        $this->assertInstanceOf(CPrenotazione::class, $result);

        // Invalid User ID
        $invalid = $this->model->getUserOngoingReservations('aisjasidahd');
        $this->assertNull($invalid);
    }

    public function testGetReservationsBySede()
    {
        // Valid User ID
        $result = $this->model->getReservationsBySede('torino', 2);
        $this->assertIsArray($result);
        foreach ($result as $prenotazione) {
            $this->assertInstanceOf(CPrenotazione::class, $prenotazione);
        }

        // Invalid count
        $invalid = $this->model->getReservationsBySede('torino', -1);
        $this->assertNull($invalid);

        // Invalid User ID
        $invalid = $this->model->getReservationsBySede('aisjasidahd', 2);
        $this->assertNull($invalid);
    }

    public function testGetAllReservationsBySede()
    {
        // Valid User ID
        $result = $this->model->getAllReservationsBySede('torino');
        $this->assertIsArray($result);
        foreach ($result as $prenotazione) {
            $this->assertInstanceOf(CPrenotazione::class, $prenotazione);
        }

        // Invalid User ID
        $invalid = $this->model->getAllReservationsBySede('aisjasidahd');
        $this->assertNull($invalid);
    }

    public function testGetReservationsByCar()
    {
        // Valid User ID
        $result = $this->model->getReservationsByCar($this::ID_MACCHINA, 2);
        $this->assertIsArray($result);
        foreach ($result as $prenotazione) {
            $this->assertInstanceOf(CPrenotazione::class, $prenotazione);
        }

        // Invalid count
        $invalid = $this->model->getReservationsByCar('99878292345061418', -1);
        $this->assertNull($invalid);

        // Invalid User ID
        $invalid = $this->model->getReservationsByCar('aisjasidahd', 2);
        $this->assertNull($invalid);
    }

    public function testGetAllReservationsByCar()
    {

        // Valid User ID
        $result = $this->model->getAllReservationsByCar($this::ID_MACCHINA);
        $this->assertIsArray($result);
        foreach ($result as $prenotazione) {
            $this->assertInstanceOf(CPrenotazione::class, $prenotazione);
        }

        // Invalid User ID
        $invalid = $this->model->getAllReservationsBySede('aisjasidahd');
        $this->assertNull($invalid);
    }

    public function testGetReservations()
    {
        // Valid Count
        $result = $this->model->getReservations(4);
        $this->assertIsArray($result);
        foreach ($result as $prenotazione) {
            $this->assertInstanceOf(CPrenotazione::class, $prenotazione);
        }

        // Invalid Count
        $invalid = $this->model->getReservations(-1);
        $this->assertNull($invalid);
    }

    public function testGetAllReservations()
    {
        // Valid Count
        $result = $this->model->getAllReservations();
        $this->assertIsArray($result);
        foreach ($result as $prenotazione) {
            $this->assertInstanceOf(CPrenotazione::class, $prenotazione);
        }
    }

    public function testGetFutureReservations()
    {
        // Valid Count
        $result = $this->model->getFutureReservations();
        // Get Now
        $now = new DateTime(date('Y-m-d', time()));
        // Assertions
        $this->assertIsArray($result);
        foreach ($result as $prenotazione) {
            $this->assertInstanceOf(CPrenotazione::class, $prenotazione);
            $this->assertGreaterThan($prenotazione->from_date, $now);
        }
    }

    public function testGetOngoingReservations()
    {
        // Valid Count
        $result = $this->model->getOngoingReservations();
        $this->assertIsArray($result);
        // get now
        $now = new DateTime(date('Y-m-d', time()));
        // Assertionss
        foreach ($result as $prenotazione) {
            $this->assertInstanceOf(CPrenotazione::class, $prenotazione);
            $this->assertGreaterThanOrEqual($prenotazione->from_date, $now);

            $this->assertLessThanOrEqual($prenotazione->to_date, $now);
        }
    }

    public function testGetPastReservations()
    {
        // Valid Count
        $now = new DateTime(date('Y-m-d', time()));
        $result = $this->model->getPastReservations();
        $this->assertIsArray($result);
        foreach ($result as $prenotazione) {
            $this->assertInstanceOf(CPrenotazione::class, $prenotazione);
            $this->assertLessThan($prenotazione->to_date, $now);
        }
    }

    // MANAGEMENT
    public function testReserve(): void
    {
        // Valid Parameters
        $this->model->reserve($this->)

        // Invalid Username
    }

    public function testEditReservation(): void
    {
        // Valid parameters

        // Invalid Username

        // Invalid Parameter

        // Disallowed parameter
    }

    public function testCancelReservation(): void
    {
        // Create reservation

        // Valid ID

        // Invalid ID
    }
}