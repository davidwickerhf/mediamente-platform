<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once ROOT_PATH . 'model/macchina.php';


abstract class MemoryTestCase extends TestCase
{
    public ?Macchina $model;
    public const ID_MACCHINA = '99860421573345291';
    public const ID_MACCHINA2 = '99860421573345290';
    public const USERNAME_UTENTE = 'davidwickerhf';
    public const ID_PRENOTAZIONE = '99878292345061418';
    public const ID_MANUTENZIONE = '99878292345061425';

    public function setUp(): void
    {
        $this->model = new Macchina;
    }

    protected function tearDown(): void
    {
        $refl = new ReflectionObject($this);
        foreach ($refl->getProperties() as $prop) {
            if (!$prop->isStatic() && 0 !== strpos($prop->getDeclaringClass()->getName(), 'PHPUnit_')) {
                $prop->setAccessible(true);
                $prop->setValue($this, null);
            }
        }
        unset($this->model);
        if (isset($this->invalid)) {
            unset($this->invalid);
        }
        if (isset($this->result)) {
            unset($this->result);
        }
    }
}