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

    public function testManutenzione()
    {
        $data =  new DateTime('2022-07-25');
        // Valid Parameters
        $result = $this->model->manutenzione($this::ID_MACCHINA, $this::USERNAME_UTENTE, $data, 'revisione', 'Benzinaio', 1000, 'Me dovete da il rimborso');
        $this->assertInstanceOf(CManutenzione::class, $result);

        // Invalid Username
        $result = $this->model->manutenzione($this::ID_MACCHINA, 'giuseppe', $data, 'revisione', 'Benzinaio', 1000, 'Me dovete da il rimborso');
        $this->assertNull($result);

        // Invalid motivazione
        $result = $this->model->manutenzione($this::ID_MACCHINA, $this::USERNAME_UTENTE, $data, 'afasjdoajdsoij', 'Benzinaio', 1000, 'Me dovete da il rimborso');
        $this->assertNull($result);
    }

    public function testEditManutenzione()
    {
        $data =  new DateTime('2022-07-25');
        // Valid Parameters
        $reservation = $this->model->manutenzione($this::ID_MACCHINA, $this::USERNAME_UTENTE, $data, 'revisione', 'Benzinaio', 1000, 'Test Edit Manutenzione');

        // Valid parameters
        $newdate =  new DateTime('2022-07-29');
        $result = $this->model->editManutenzione($reservation->id, array(
            'data' => $newdate,
            'tipologia' => 'cambiogomme',
            'commento' => 'Modificato con successo'
        ));
        $this->assertInstanceOf(CManutenzione::class, $result);
        $this->assertEquals('cambiogomme', $result->tipologia);
        $this->assertEquals($newdate, $result->data);

        // Invalid ID
        $result = $this->model->editManutenzione('12341412313', array(
            'data' => $newdate,
            'tipologia' => 'cambiogomme',
            'commento' => 'Modificato con successo'
        ));
        $this->assertNull($result);

        // Invalid and Disallowed Parameter
        $result = $this->model->editManutenzione($reservation->id, array(
            'data' => $newdate,
            'tipologia' => 'tagliando',
            'commento' => 'Modificato con successo',
            'id' => 'aisjdijiajd'
        ));
        $this->assertInstanceOf(CManutenzione::class, $result);
        $this->assertEquals('tagliando', $result->tipologia);
    }

    public function testDeleteManutenzione()
    {
        // Create reservation
        $data =  new DateTime('2022-07-25');
        // Valid Parameters
        $manutenzione = $this->model->manutenzione($this::ID_MACCHINA, $this::USERNAME_UTENTE, $data, 'revisione', 'Benzinaio', 1000, 'Test Delete');

        // Valid ID
        $result = $this->model->deleteManutenzione($manutenzione->id);
        $this->assertTrue($result);
    }
}