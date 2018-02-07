<?php

namespace Test;

use Mvkasatkin\mocker\Mock;
use Mvkasatkin\mocker\Mocker;
use My\SomeClass;

class MockerClassTest extends MockerTestCase
{

    public function testDummyClass()
    {
        $mock = Mocker::get(SomeClass::class);
        $this->assertInstanceOf(SomeClass::class, $mock);
    }

    public function testStubPublicMethod()
    {
        /** @var SomeClass $mock */
        $mock = Mocker::get(SomeClass::class, [
            Mocker::method('publicMethod', 1, 'y')->returns('x')
        ]);
        $this->assertEquals('x', $mock->publicMethod('y'));
    }

    public function testStubInternalProtectedMethod()
    {
        /** @var SomeClass $mock */
        $mock = Mocker::get(SomeClass::class, [
            Mocker::method('protectedMethod', 1, ['y'])->returns('x')
        ]);
        $this->assertEquals('xX', $mock->publicMethod('y'));
    }

    public function testStubInternalPrivateMethod()
    {
        /** @var SomeClass $mock */
        $mock = Mocker::get(SomeClass::class, [
            Mocker::method('privateMethod', 0, ['y'])->returns('x') // no sense
        ]);
        $this->assertEquals('yZYX', $mock->publicMethod('y')); // NO MOCK for private methods
        $this->assertEquals('y', Mocker::getProperty($mock, 'protectedProperty'));
        $this->assertEquals('y', Mocker::getProperty($mock, 'privateProperty'));
    }

    public function testReturnsSelf()
    {
        /** @var SomeClass $mock */
        $mock = Mocker::get(SomeClass::class, [
            Mocker::method('returnSelf', 1)->returnsSelf()
        ]);
        $result = $mock->returnSelf();
        $this->assertEquals($mock, $result);
        $this->assertSame($mock, $result);
    }
    public function testMethodWithMap()
    {
        /** @var SomeClass $mock */
        $mock = Mocker::get(SomeClass::class, [
            Mocker::method('checkMap', 5)->returnsMap([
                ['arg1', 'arg2', 'arg3', 'A'],
                ['arg1', 'arg2', null, 'B'],
                ['arg1', null, null, 'C'],
                [null, null, null, 'D'],
            ]),
        ]);
        $this->assertEquals('A', $mock->checkMap('arg1', 'arg2', 'arg3'));
        $this->assertEquals('B', $mock->checkMap('arg1', 'arg2'));
        $this->assertEquals('C', $mock->checkMap('arg1'));
        $this->assertEquals('D', $mock->checkMap());
        $this->assertEquals(null, $mock->checkMap('a', 'b', 'c'));
    }

}