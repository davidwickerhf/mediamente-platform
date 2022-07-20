<?php

declare(strict_types=1);



require_once './app.config.php';
require_once ROOT_PATH . 'tests/MemoryTestCase.php';
require_once ROOT_PATH . 'classes/macchina.class.php';
require_once ROOT_PATH . 'classes/manutenzione.class.php';
require_once ROOT_PATH . 'model/macchina.php';

/**
 * Unit Test class for testing Database connection for 
 *  the table: `manutenzioni`.
 * PHP Version 7.4.
 * @uses PHPUnit Version 9.
 *  Install via composer
 * @see https://phpunit.de/getting-started/phpunit-9.html
 *
 * @author    David Henry Francis Wicker (https://github.com/davidwickerhf) <davidwickerhf@gmail.com>
 * @license   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */
final class ManutenzioniDBTest extends MemoryTestCase
{
    public function testGetManutenzione(): void
    {
        //Valid ID
        $result = $this->model->getManutenzione($this::ID_MANUTENZIONE);
        $this->assertInstanceOf(CManutenzione::class, $result);

        //Get reservation invalid ID
        $this->assertNull(
            $this->model->getManutenzione('99060418373345288'),
            'Get reservation by invaid ID returns null'
        );
    }

    public function testGetCarManutenzioni(): void
    {
        // Valid ID
        $result = $this->model->getCarManutenzioni($this::ID_MACCHINA);
        $this->assertIsArray($result);
        foreach ($result as $prenotazione) {
            $this->assertInstanceOf(CManutenzione::class, $prenotazione);
        }

        // Invalid ID
        $invalid = $this->model->getCarManutenzioni('298491989284');
        $this->assertNull($invalid);
    }

    public function testGetCarLastManutenzione()
    {
        // Valid ID and type
        $result = $this->model->getCarLastManutenzione($this::ID_MACCHINA, 'revisione');
        $this->assertInstanceOf(CManutenzione::class, $result);

        // Invalid type
        $result = $this->model->getCarLastManutenzione('1291023801283012', 'revisione');
        $this->assertNull($result);

        // Type not present
        $result = $this->model->getCarLastManutenzione($this::ID_MACCHINA, 'cambiogomme');
        $this->assertNull($result);

        // Invalid ID
        $invalid = $this->model->getCarLastManutenzione('298491989284', 'revisione');
        $this->assertNull($invalid);
    }
}