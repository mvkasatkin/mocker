<?php

namespace Test;

use Mvkasatkin\mocker\Mocker;
use My\SomeClass;

class MockerClassTest extends MockerTestCase
{

    public function testDummyClass()
    {
        $mock = Mocker::create(SomeClass::class);
        $this->assertInstanceOf(SomeClass::class, $mock);
    }

    public function testStubPublicMethod()
    {
        /** @var SomeClass $mock */
        $mock = Mocker::create(SomeClass::class, [
            Mocker::method('publicMethod', 1, 'y')->returns('x')
        ]);
        $this->assertEquals('x', $mock->publicMethod('y'));
    }

    public function testStubInternalProtectedMethod()
    {
        /** @var SomeClass $mock */
        $mock = Mocker::create(SomeClass::class, [
            Mocker::method('protectedMethod', 1, ['y'])->returns('x')
        ]);
        $this->assertEquals('xX', $mock->publicMethod('y'));
    }

    public function testStubInternalPrivateMethod()
    {
        /** @var SomeClass $mock */
        $mock = Mocker::create(SomeClass::class, [
            Mocker::method('privateMethod', 0, ['y'])->returns('x') // no sense
        ]);
        $this->assertEquals('yZYX', $mock->publicMethod('y')); // NO MOCK for private methods
        $this->assertEquals('y', Mocker::getProperty($mock, 'protectedProperty'));
        $this->assertEquals('y', Mocker::getProperty($mock, 'privateProperty'));
    }

}