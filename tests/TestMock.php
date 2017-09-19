<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use PHPUnit\Util\Test;

class TestMock extends TestCase
{

    public function testMockForTrait()
    {
        $this->getMockForTrait();
        $this->getObjectForTrait();
    }

}