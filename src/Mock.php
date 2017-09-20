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
    public function create()
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

        foreach ($this->methods as $name => $methods) {
            /** @var Method $method */
            foreach ($methods as $method) {
                $expectCallCount = $method->getExpectCallCount();
                $m = $mock
                    ->expects($expectCallCount !== null
                        ? new PHPUnit_Framework_MockObject_Matcher_InvokedCount($expectCallCount)
                        : new PHPUnit_Framework_MockObject_Matcher_AnyInvokedCount)
                    ->method($name);

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
                    $m->willReturnMap($willReturnMap);
                } else {
                    $m->willReturn($willReturn);
                }

                if (is_array($with)) {
                    call_user_func_array([$m, 'with'], $with);
                }
            }
        }

        return $mock;
    }
}
