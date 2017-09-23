<?php

namespace Mvkasatkin\mocker;

use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_Generator;

class Mocker
{
    /** @var TestCase */
    protected static $testCase;

    /**
     * @param TestCase $testCase
     */
    public static function init(TestCase $testCase)
    {
        self::$testCase = $testCase;
    }

    /**
     * @param $classOrInterface
     * @param array $configItems
     * @param array $args - if not null - will be called constructor
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     * @throws \Exception
     */
    public static function create(
        $classOrInterface,
        array $configItems = [],
        $args = null
    ): \PHPUnit_Framework_MockObject_MockObject {
        $generator = new PHPUnit_Framework_MockObject_Generator();
        $mock = (new Mock($generator, $classOrInterface, $args, $configItems))->create();
        self::getTestCase()->registerMockObject($mock);
        return $mock;
    }

    /**
     * @param $name
     * @param int|null $count
     * @param null $with
     *
     * @return Method
     */
    public static function method($name, int $count = null, $with = null): Method
    {
        $method = new Method($name);
        if ($count !== null) {
            $method->expect($count);
        }
        if ($with !== null) {
            $method->with($with);
        }
        return $method;
    }

    /**
     * @param $object
     * @param $propertyName
     * @param $value
     *
     * @throws \ReflectionException
     */
    public static function setProperty($object, $propertyName, $value)
    {
        $reflectionClass = new \ReflectionClass(get_class($object));
        while ($reflectionClass && !$reflectionClass->hasProperty($propertyName)) {
            $reflectionClass = $reflectionClass->getParentClass();
        }
        if ($reflectionClass && $reflectionClass->hasProperty($propertyName)) {
            $reflectionProperty = $reflectionClass->getProperty($propertyName);
            $reflectionProperty->setAccessible(true);
            $reflectionProperty->setValue($object, $value);
        }
    }

    /**
     * @param $object
     * @param $propertyName
     *
     * @return mixed
     * @throws \ReflectionException
     */
    public static function getProperty($object, $propertyName)
    {
        $reflectionClass = new \ReflectionClass(get_class($object));
        while ($reflectionClass && !$reflectionClass->hasProperty($propertyName)) {
            $reflectionClass = $reflectionClass->getParentClass();
        }
        if ($reflectionClass && $reflectionClass->hasProperty($propertyName)) {
            $reflectionProperty = $reflectionClass->getProperty($propertyName);
            $reflectionProperty->setAccessible(true);
            return $reflectionProperty->getValue($object);
        }
        return null;
    }

    /**
     * @param $object
     * @param $methodName
     * @param array $args
     *
     * @return mixed
     * @throws \ReflectionException
     */
    public static function invoke($object, $methodName, array $args = [])
    {
        $reflectionClass = new \ReflectionClass(get_class($object));
        while ($reflectionClass && !$reflectionClass->hasMethod($methodName)) {
            $reflectionClass = $reflectionClass->getParentClass();
        }
        if ($reflectionClass && $reflectionClass->hasMethod($methodName)) {
            $reflectionMethod = $reflectionClass->getMethod($methodName);
            $reflectionMethod->setAccessible(true);
            return call_user_func_array([$reflectionMethod, 'invoke'], array_merge([$object], $args));
        }
        return null;
    }

    /**
     * @return TestCase
     * @throws \Exception
     */
    protected static function getTestCase(): TestCase
    {
        if (self::$testCase === null) {
            throw new \RuntimeException('TestCase not found. Use Mocker::setTestCase');
        }
        return self::$testCase;
    }
}
