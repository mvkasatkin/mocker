# Mocker

[![Build Status](https://travis-ci.org/mvkasatkin/mocker.svg?branch=master)](https://travis-ci.org/mvkasatkin/mocker)
[![Coverage Status](https://coveralls.io/repos/github/mvkasatkin/mocker/badge.svg?branch=master)](https://coveralls.io/github/mvkasatkin/mocker?branch=master)
[![Latest Stable Version](https://poser.pugx.org/mvkasatkin/mocker/version)](https://packagist.org/packages/mvkasatkin/mocker)
[![License](https://poser.pugx.org/mvkasatkin/mocker/license)](https://packagist.org/packages/mvkasatkin/mocker)

Helper for convenient work with PHPUnit mocks.<br>
It uses the library **phpunit/phpunit-mock-objects** supplied with PHPUnit. 

## Install

```
composer require mvkasatkin/mocker
```

## Initialize

```php
    public function setUp()
    {
        parent::setUp();
        Mocker::init($this);
    }
```

## Simple test-double

Works with regular classes, abstract classes and interfaces.

```php
        $mock = Mocker::create(SomeClass::class);
        $this->assertInstanceOf(SomeClass::class, $mock);
```

## Test-double with methods

```php
        $mock = Mocker::create(SomeClass::class, [
            Mocker::method('firstMethod')->returns(true),
            Mocker::method('secondMethod', 1)->returns(true),
            Mocker::method('thirdMethod', 1, [$param1, $param2])->returns($value),
            Mocker::method('fourthMethod', 1)->with([$param1, $param2])->returns($value),
        ]);
```

In this example:

**$mock->firstMethod** - can be called any number of times with any parameters and returns true<br>
**$mock->secondMethod** - must be called once and returns true<br>
**$mock->thirdMethod** - must be called once with the parameters $param1, $param2 and returns $value<br>
**$mock->fourthMethod** - must be called once with the parameters $param1, $param2 and returns $value<br>

## Test-double with/return map:

```php
        /** @var SomeClass $mock */
        $mock = Mocker::create(SomeClass::class, [
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
```

*Mocking of private methods is impossible.*<br> 
*Mocking of protected methods is possible, but it's not a «best practice».*<br> 

## Test-double with constructor call

```php
        $mock = Mocker::create(SomeClass::class, [...], [$arg1, $arg2]);
        $this->assertInstanceOf(SomeClass::class, $mock);
```

Constructor of the SomeClass will be invoked with args: $arg1, $arg2

## Protected properties and methods

Despite the fact that testing internal implementation of classes is not the best practice, sometimes it is still necessary to set or verify a protected property, or to call a protected method.

It works both with protected properties and methods, and with private: 

```php
        $o = new SomeClass();
        Mocker::setProperty($o, 'protectedProperty', 'a');
        Mocker::setProperty($o, 'privateProperty', 'b');
        $this->assertEquals('a', Mocker::getProperty($o, 'protectedProperty'));
        $this->assertEquals('b', Mocker::getProperty($o, 'privateProperty'));
        $this->assertEquals('aZ', Mocker::invoke($o, 'privateMethod', ['a']));
```
