<?php

namespace Mvkasatkin\mocker;

use PHPUnit\Framework\MockObject\Generator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

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
     * @return MockObject|mixed
     */
    public static function create($classOrInterface, array $configItems = [], $args = null)
    {
        $generator = new Generator();
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
    public static function method($name, int $count = null, $with = null)
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
    protected static function getTestCase()
    {
        if (self::$testCase === null) {
            throw new \Exception('TestCase not found. Use Mocker::setTestCase');
        }
        return self::$testCase;
    }
}
