<?php

namespace Test;

use Mvkasatkin\mocker\Mocker;
use My\SomeAbstractClass;

class MockerAbstractTest extends MockerTestCase
{

    public function testDummyAbstractClass()
    {
        $mock = Mocker::create(SomeAbstractClass::class);
        $this->assertInstanceOf(SomeAbstractClass::class, $mock);
    }

    public function testStubAbstractMethod()
    {
        /** @var SomeAbstractClass $mock */
        $mock = Mocker::create(SomeAbstractClass::class, [
            Mocker::method('abstractMethod', 1)->with(['y'])->returns('x')
        ]);
        $this->assertEquals('x', $mock->abstractMethod('y'));
    }

    public function testStubPublicMethod()
    {
        /** @var SomeAbstractClass $mock */
        $mock = Mocker::create(SomeAbstractClass::class, [
            Mocker::method('publicMethod', 1)->with(['y'])->returns('x')
        ]);
        $this->assertEquals('x', $mock->publicMethod('y'));
    }

    public function testStubInternalProtectedMethod()
    {
        /** @var SomeAbstractClass $mock */
        $mock = Mocker::create(SomeAbstractClass::class, [
            Mocker::method('protectedMethod', 1)->with(['y'])->returns('x')
        ]);
        $this->assertEquals('xX', $mock->publicMethod('y'));
    }

    public function testStubInternalPrivateMethod()
    {
        /** @var SomeAbstractClass $mock */
        $mock = Mocker::create(SomeAbstractClass::class, [
            Mocker::method('privateMethod')->with(['y'])->returns('x') // no sense
        ]);
        $this->assertEquals('yZYX', $mock->publicMethod('y')); // NO MOCK for private methods
    }

}