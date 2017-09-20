<?php

namespace Test;

use Mvkasatkin\mocker\Mocker;
use My\SomeInterface;

class MockerInterfaceTest extends MockerTestCase
{

    public function testDummyInterface()
    {
        $mock = Mocker::create(SomeInterface::class);
        $this->assertInstanceOf(SomeInterface::class, $mock);
    }

    public function testStubPublicMethod()
    {
        /** @var SomeInterface $mock */
        $mock = Mocker::create(SomeInterface::class, [
            Mocker::method('interfaceMethod', 1)->with(['y'])->returns('x')
        ]);
        $this->assertEquals('x', $mock->interfaceMethod('y'));
    }

}
