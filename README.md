# Mocker

[![Build Status](https://travis-ci.org/mvkasatkin/mocker.svg?branch=master)](https://travis-ci.org/mvkasatkin/mocker)
[![Coverage Status](https://coveralls.io/repos/github/mvkasatkin/mocker/badge.svg?branch=master)](https://coveralls.io/github/mvkasatkin/mocker?branch=master)

Хэлпер для удобной работы с моками PHPUnit. Использует поставляемую с PHPUnit библиотеку **phpunit/phpunit-mock-objects**. 

## Подключение

```php
    public function setUp()
    {
        parent::setUp();
        Mocker::init($this);
    }
```

## Дублирование классов

Работает с обычными классами, абстрактными классами и интерфейсами

```php
        $mock = Mocker::create(SomeClass::class);
        $this->assertInstanceOf(SomeClass::class, $mock);
```

## Дублирование классов и методов

```php
        $mock = Mocker::create(SomeClass::class, [
            Mocker::method('firstMethod')->returns(true),
            Mocker::method('secondMethod', 1)->returns(true),
            Mocker::method('thirdMethod', 1, [$param1, $param2])->returns($value),
            Mocker::method('fourthMethod', 1)->with([$param1, $param2])->returns($value),
        ]);
```

В этом примере:

**$mock->firstMethod** - может быть вызван любое количество раз с любыми параметрами и вернет true<br>
**$mock->secondMethod** - должен быть вызван один раз и вернет true<br>
**$mock->thirdMethod** - должен быть вызван один раз с параметрами $param1, $param2 и вернет true<br>
**$mock->fourthMethod** - должен быть вызван один раз с параметрами $param1, $param2 и вернет $value<br>

*Дубрирование приватных методов невозможно.*<br> 
*Дубрирование защищенных методов возможно, но не является «best practice».*<br> 

### Защищенные свойства и методы

Несмотря на то, что тестирование внутренней реализации классов не является лучшей практикой, иногда всё же требуется установить или проверить защищенное свойство, либо вызвать защищенный метод.

Работает как с защищенными свойствами и методами, так и с приватными: 

```php
        $o = new SomeClass();
        Mocker::setProperty($o, 'protectedProperty', 'a');
        Mocker::setProperty($o, 'privateProperty', 'b');
        $this->assertEquals('a', Mocker::getProperty($o, 'protectedProperty'));
        $this->assertEquals('b', Mocker::getProperty($o, 'privateProperty'));
        $this->assertEquals('aZ', Mocker::invoke($o, 'privateMethod', ['a']));
```
