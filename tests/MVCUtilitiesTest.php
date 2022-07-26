<?php

declare(strict_types=1);

require_once './app.config.php';
require_once ROOT_PATH . 'tests/MemoryTestCase.php';
require_once ROOT_PATH . 'classes/prenotazione.class.php';
require_once ROOT_PATH . 'model/macchina.php';

/**
 * Unit Test class for testing Database connection for MVC utilities.
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
final class MVCUtilitiesTest extends MemoryTestCase
{
    public function testGetAvailableCars()
    {
        $disponibili = $this->model->getAvailableCars(new DateTime(date('Y-m-d', time())));
        $this->assertIsArray($disponibili);

        $disponibili2 = $this->model->getAvailableCars(new DateTime(date('Y-m-d', strtotime('+10 days'))));
        $this->assertIsArray($disponibili2);

        $this->assertNotEquals($disponibili, $disponibili2);
    }

    public function testGetReservedCars()
    {
        $prenotate = $this->model->getReservedCars(new DateTime(date('Y-m-d', time())));
        $this->assertIsArray($prenotate);

        $prenotate = $this->model->getReservedCars(new DateTime(date('Y-m-d', strtotime('+1 days'))));
        $this->assertIsArray($prenotate);
    }
}