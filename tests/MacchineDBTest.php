<?php

declare(strict_types=1);

require_once './app.config.php';
require_once ROOT_PATH . 'tests/MemoryTestCase.php';
require_once ROOT_PATH . 'classes/macchina.class.php';
require_once ROOT_PATH . 'model/macchina.php';

/**
 * Unit Test class for testing Database connection for 
 *  the table: `macchine`.
 * PHP Version 7.4.
 * @uses PHPUnit Version 9.
 *  Install via composer
 * @see https://phpunit.de/getting-started/phpunit-9.html
 *
 * @author    David Henry Francis Wicker (https://github.com/davidwickerhf) <davidwickerhf@gmail.com>
 * @license   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */
final class MacchineDBTest extends MemoryTestCase
{
    // QUERIES
    public function testGetCar(): void
    {

        // Valid ID
        $this->assertInstanceOf(CMacchina::class, $this->model->getCar($this::ID_MACCHINA), 'Get car by ID returns a car object');

        // Invalid ID
        $this->assertNull($this->model->getCar('99060418373345288'), 'Get car by false ID returns null');
    }

    public function testGetAllCars(): void
    {

        $result = $this->model->getAllCars();
        $this->assertIsArray($result, 'getAllCars returns an array.');
        foreach ($result as $car) {
            $this->assertInstanceOf(CMacchina::class, $car, 'getAllCars item is a car object');
        }
    }

    public function testGetCars(): void
    {

        $result = $this->model->getCars(2);
        $this->assertIsArray($result, 'getCars returns an array.');
        foreach ($result as $car) {
            $this->assertInstanceOf(CMacchina::class, $car, 'getCars item is a car object');
        }
        $this->assertEquals(2, count($result), 'getCars returns correct amount of cars.');
    }

    public function testGetCarsBySede(): void
    {

        $result = $this->model->getCarsBySede('torino');
        $this->assertIsArray($result, 'getCarsBySede returns an array.');
        foreach ($result as $car) {
            $this->assertInstanceOf(CMacchina::class, $car, 'getCarsBySede item is a car object');
            $this->assertEquals('torino', $car->sede, 'getCarsBySede returns correct location');
        }
    }


    // MANAGEMENT
    public function testRegister(): void
    {

        $result = $this->model->register($this::USERNAME_UTENTE, 'Fiat', '500', 'torino', 'Test registerCar');
        $this->assertInstanceOf(CMacchina::class, $result);
        $this->assertEquals('torino', $result->sede);

        // Test with invalid username
        $result = $this->model->register('adsdadsd', 'Fiat', '500', 'torino', 'Test registerCar');
        $this->assertNull($result);
    }

    public function testEdit(): void
    {

        $car = $this->model->register($this::USERNAME_UTENTE, 'OLAOLA', 'Test', 'torino', 'Test Car for EDIT function Test');

        // Test with valid ID and Args
        $updated = $this->model->edit($car->id, array('modello' => 'Panda'));
        $this->assertInstanceOf(CMacchina::class, $updated);
        $this->assertEquals('Panda', $updated->modello);

        //Test with invalid ID
        $updated2 = $this->model->edit('12141231214241231', array('modello' => 'Panda'));
        $this->assertNull($updated2);

        // Test with invalid property
        $updated3 = $this->model->edit($car->id, array('mmodello' => 'Panda'));
        $this->assertInstanceOf(CMacchina::class, $updated3);
        $this->assertObjectNotHasAttribute('mmodello', $updated3);

        // Test with disallowed property
        $updated4 = $this->model->edit($car->id, array('id' => '12319823981'));
        $this->assertInstanceOf(CMacchina::class, $updated4);
        $this->assertEquals($car->id, $updated4->id);

        // Test with valid, invalid and disallowed property
        $updated5 = $this->model->edit($car->id, array('modello' => 'Freemont', 'id' => '12319823981', 'asdasd' => 'adsadds'));
        $this->assertInstanceOf(CMacchina::class, $updated5);
        $this->assertEquals('Freemont', $updated5->modello);
    }

    public function testArchive(): void
    {

        // Test with valid ID
        $car = $this->model->archive($this::USERNAME_UTENTE, $this::ID_MACCHINA2);
        $this->assertInstanceOf(CMacchina::class, $car);
        $this->assertTrue($car->archiviata);

        // Test with invalid ID
        $result = $this->model->archive($this::USERNAME_UTENTE, '29193913292093');
        $this->assertNull($result);
    }

    public function testUnarchive(): void
    {

        // Test with valid ID
        $car = $this->model->unarchive($this::ID_MACCHINA2);
        $this->assertInstanceOf(CMacchina::class, $car);
        $this->assertFalse($car->archiviata);

        // Test with invalid ID
        $result = $this->model->unarchive($this::USERNAME_UTENTE, '29193913292093');
        $this->assertNull($result);
    }

    public function testDelete(): void
    {

        // Create Test Car
        $car = $this->model->register($this::USERNAME_UTENTE, 'Fiat', 'ToDelete', 'torino', 'Test car for delete function');

        // Test with valid ID
        $result = $this->model->delete($car->id);
        $this->assertTrue($result);

        // Test with invalid ID
        $result = $this->model->delete('29193913292093');
        $this->assertFalse($result);
    }
}