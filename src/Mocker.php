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
     * @param array $args - if not null - will be called constructor
     * @param array $configItems
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public static function mock($classOrInterface, $args = null, array $configItems = [])
    {
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
