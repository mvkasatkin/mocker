<?php

namespace Test;

use Mvkasatkin\mocker\Mocker;
use My\ServiceA;
use My\ServiceAbstract;
use My\ServiceInterface;

class MockingDummyTest extends \PHPUnit\Framework\TestCase
{

    public function setUp()
    {
        parent::setUp();
        Mocker::init($this);
    }

    public function testDummyClass()
    {
        $mock = Mocker::mock(ServiceA::class);
        $this->assertInstanceOf(ServiceA::class, $mock);
    }

    public function testDummyAbstractClass()
    {
        $mock = Mocker::mock(ServiceAbstract::class);
        $this->assertInstanceOf(ServiceAbstract::class, $mock);
    }

    public function testDummyInterface()
    {
        $mock = Mocker::mock(ServiceInterface::class);
        $this->assertInstanceOf(ServiceInterface::class, $mock);
    }

}
