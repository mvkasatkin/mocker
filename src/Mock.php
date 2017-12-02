<?php

namespace Mvkasatkin\mocker;

use PHPUnit\Framework\MockObject\Generator;
use PHPUnit\Framework\MockObject\Matcher\AnyInvokedCount;
use PHPUnit\Framework\MockObject\Matcher\InvokedCount;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Stub\ReturnSelf;

class Mock
{

    /**
     * @var Generator
     */
    private $generator;
    private $classOrInterface;
    private $methods = [];
    private $args;

    /**
     * Mock constructor.
     *
     * @param Generator $generator
     * @param $classOrInterface
     * @param array $args
     * @param array $configItems
     */
    public function __construct(
        Generator $generator,
        $classOrInterface,
        $args = null,
        array $configItems = []
    ) {
        $this->generator = $generator;
        $this->args = $args;
        $this->classOrInterface = $classOrInterface;

        foreach ($configItems as $item) {
            if ($item instanceof Method) {
                $this->addMethod($item);
            }
        }
    }

    /**
     * @param Method $method
     */
    public function addMethod(Method $method)
    {
        $methodName = $method->getName();
        if (!isset($this->methods[$methodName])) {
            $this->methods[$methodName] = [];
        }
        $this->methods[$methodName][] = $method;
    }

    /**
     * @return MockObject
     * @throws \Exception
     */
    public function create()
    {
        $mock = $this->createMock();
        foreach ($this->methods as $name => $methods) {
            /** @var Method $method */
            foreach ($methods as $method) {
                $this->addMockMethod($mock, $method, $name);
            }
        }

        return $mock;
    }

    /**
     * @return MockObject
     * @throws \Exception
     */
    protected function createMock()
    {
        if (class_exists($this->classOrInterface) || interface_exists($this->classOrInterface)) {
            $mock = $this->generator->getMockForAbstractClass(
                $this->classOrInterface,
                $this->args ? $this->args : [],
                '',
                $this->args !== null,
                true,
                true,
                array_keys($this->methods),
                true
            );
        } else {
            throw new \Exception('Class or interface not found: ' . $this->classOrInterface);
        }
        return $mock;
    }

    /**
     * @param $mock
     * @param $method
     * @param $name
     *
     * @throws \Exception
     */
    protected function addMockMethod(\PHPUnit_Framework_MockObject_MockObject $mock, Method $method, $name)
    {
        $expectCallCount = $method->getExpectCallCount();
        $mockMethod = $mock->expects(
            $expectCallCount !== null
                ? new InvokedCount($expectCallCount)
                : new AnyInvokedCount()
        )->method($name);

        $with = $method->getWith();
        $willReturn = $method->getWillReturn();
        $willReturnMap = $method->getWillReturnMap();
        if ($willReturnMap) {
            if ($willReturn) {
                throw new \Exception('Cannot use both returns() and returnsWithMap()');
            }
            if ($with) {
                throw new \Exception('Cannot use both with() and returnsWithMap()');
            }
            $mockMethod->willReturnMap($willReturnMap);
        } elseif ($willReturn instanceof ReturnSelf) {
            $mockMethod->willReturnSelf();
        } else {
            $mockMethod->willReturn($willReturn);
        }

        if (is_array($with)) {
            call_user_func_array([$mockMethod, 'with'], $with);
        }
    }
}
