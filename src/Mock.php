<?php

namespace Mvkasatkin\mocker;

use PHPUnit_Framework_MockObject_Matcher_AnyInvokedCount;
use PHPUnit_Framework_MockObject_Matcher_InvokedCount;

class Mock
{

    /**
     * @var \PHPUnit_Framework_MockObject_Generator
     */
    private $generator;
    private $classOrInterface;
    private $methods = [];
    private $args;

    /**
     * Mock constructor.
     *
     * @param \PHPUnit_Framework_MockObject_Generator $generator
     * @param $classOrInterface
     * @param array $args
     * @param array $configItems
     */
    public function __construct(
        \PHPUnit_Framework_MockObject_Generator $generator,
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
     * @return \PHPUnit_Framework_MockObject_MockObject
     * @throws \Exception
     */
    public function create(): \PHPUnit_Framework_MockObject_MockObject
    {
        $mock = $this->createMock();
        foreach ($this->methods as $name => $methods) {
            /** @var Method[] $methods */
            foreach ($methods as $method) {
                $this->addMockMethod($mock, $method, $name);
            }
        }

        return $mock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     * @throws \Exception
     */
    protected function createMock(): \PHPUnit_Framework_MockObject_MockObject
    {
        if (class_exists($this->classOrInterface) || interface_exists($this->classOrInterface)) {
            $mock = $this->generator->getMockForAbstractClass(
                $this->classOrInterface,
                $this->args ?: [],
                '',
                $this->args !== null,
                true,
                true,
                array_keys($this->methods)
            );
        } else {
            throw new \RuntimeException('Class or interface not found: ' . $this->classOrInterface);
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
            ? new PHPUnit_Framework_MockObject_Matcher_InvokedCount($expectCallCount)
            : new PHPUnit_Framework_MockObject_Matcher_AnyInvokedCount
        )->method($name);

        $with = $method->getWith();
        $willReturn = $method->getWillReturn();
        $willReturnMap = $method->getWillReturnMap();
        if ($willReturnMap) {
            if ($willReturn) {
                throw new \RuntimeException('Cannot use both returns() and returnsWithMap()');
            }
            if ($with) {
                throw new \RuntimeException('Cannot use both with() and returnsWithMap()');
            }
            $mockMethod->willReturnMap($willReturnMap);
        } else {
            $mockMethod->willReturn($willReturn);
        }

        if (is_array($with)) {
            call_user_func_array([$mockMethod, 'with'], $with);
        }
    }
}
