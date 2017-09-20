<?php

namespace Test;

use Mvkasatkin\mocker\Mocker;
use My\SomeClass;

class MockerTest extends MockerTestCase
{

    public function testSetGetPropertyOnClassObject()
    {
        $o = new SomeClass();
        Mocker::setProperty($o, 'protectedProperty', 'a');
        Mocker::setProperty($o, 'privateProperty', 'b');
        $this->assertEquals('a', Mocker::getProperty($o, 'protectedProperty'));
        $this->assertEquals('b', Mocker::getProperty($o, 'privateProperty'));
    }

    public function testSetGetPropertyOnMock()
    {
        $o = Mocker::create(SomeClass::class);
        Mocker::setProperty($o, 'protectedProperty', 'a');
        Mocker::setProperty($o, 'privateProperty', 'b');
        $this->assertEquals('a', Mocker::getProperty($o, 'protectedProperty'));
        $this->assertEquals('b', Mocker::getProperty($o, 'privateProperty'));
    }

    public function testInvokeOnClassObject()
    {
        $o = new SomeClass();
        $this->assertEquals('aZ', Mocker::invoke($o, 'privateMethod', ['a']));
    }

    public function testInvokeOnMock()
    {
        $o = Mocker::create(SomeClass::class);
        $this->assertEquals('aZ', Mocker::invoke($o, 'privateMethod', ['a']));
    }

}