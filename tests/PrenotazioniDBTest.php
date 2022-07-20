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
        $result = $this->model->getReservation('99878292345061418');
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
        $result = $this->model->getAllUserReservations('davidwickerhf');
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
        $result = $this->model->getUserReservations('davidwickerhf', 2);
        $this->assertIsArray($result);
        foreach ($result as $prenotazione) {
            $this->assertInstanceOf(CPrenotazione::class, $prenotazione);
        }

        // Invalid count
        $invalid = $this->model->getUserReservations('davidwickerhf', -1);

        $this->assertNull($invalid);

        // Invalid User ID
        $invalid = $this->model->getUserReservations('aisjasidahd', 2);
        $this->assertNull($invalid);
    }

    public function testGetUserOngoingReservation()
    {

        // Valid User ID
        $result = $this->model->getUserOngoingReservations('davidwickerhf');
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



    // MANAGEMENT
    // public function testReserve(): void
    // {
    // }

    // public function testEditReservation(): void
    // {
    // }

    // public function testCancelReservation(): void
    // {
    // }
}