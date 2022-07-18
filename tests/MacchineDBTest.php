<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNull;

require_once './app.config.php';
require_once ROOT_PATH . 'classes/macchina.class.php';
require_once ROOT_PATH . 'classes/prenotazione.class.php';
require_once ROOT_PATH . 'classes/manutenzione.class.php';
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
final class MacchineDBTest extends TestCase
{
    // QUERIES
    public function testGetCar(): void
    {
        $model = new Macchina;
        $this->assertInstanceOf(CMacchina::class, $model->getCar('99860421573345288'), 'Get car by ID returns a car object');
        assertNull($model->getCar('99060418373345288'), 'Get car by false ID returns null');
    }

    public function testGetAllCars(): void
    {
        $model = new Macchina;
        $cars = $model->getAllCars();
        $this->assertIsArray($cars, 'getAllCars returns an array.');
        foreach ($cars as $car) {
            $this->assertInstanceOf(CMacchina::class, $car, 'getAllCars item is a car object');
        }
    }

    public function testGetCars(): void
    {
        $model = new Macchina;
        $cars = $model->getCars(3);
        $this->assertIsArray($cars, 'getCars returns an array.');
        foreach ($cars as $car) {
            $this->assertInstanceOf(CMacchina::class, $car, 'getCars item is a car object');
        }
        assertEquals(3, count($cars), 'getCars returns correct amount of cars.');
    }

    public function testGetCarsBySede(): void
    {
        $model = new Macchina;
        $cars = $model->getCarsBySede('torino');
        $this->assertIsArray($cars, 'getCarsBySede returns an array.');
        foreach ($cars as $car) {
            $this->assertInstanceOf(CMacchina::class, $car, 'getCarsBySede item is a car object');
            $this->assertEquals('torino', $car->sede, 'getCarsBySede returns correct location');
        }
    }


    // // MANAGEMENT
    // public function testRegister(): void
    // {
    // }

    // public function testArchive(): void
    // {
    // }

    // public function testUnarchive(): void
    // {
    // }

    // public function testDelete(): void
    // {
    // }
}