<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once './app.config.php';
require_once ROOT_PATH . 'classes/macchina.class.php';
require_once ROOT_PATH . 'classes/prenotazione.class.php';
require_once ROOT_PATH . 'classes/manutenzione.class.php';
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
final class PrenotazioniDBTest extends TestCase
{
    public function testReserve(): void
    {
    }

    public function testEditReservation(): void
    {
    }

    public function testCancelReservation(): void
    {
    }
}