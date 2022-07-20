<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once ROOT_PATH . 'model/macchina.php';


abstract class MemoryTestCase extends TestCase
{
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