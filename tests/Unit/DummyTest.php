<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;

class DummyTest extends TestCase
{
    /**
     * Dummy
     *
     * @return void
     */
    public function testDummy(): void
    {
        $this->assertEquals('DUMMY', 'DUMMY');
    }
}
