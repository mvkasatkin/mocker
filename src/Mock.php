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
    /**
     * @var Method[]
     */
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
                $this->methods[$item->getName()] = $item;
            }
        }
    }

    /**
     * @param Method $method
     */
    public function addMethod(Method $method)
    {
        $this->methods[$method->getName()] = $method;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     * @throws \Exception
     */
    public function create()
    {
        if (class_exists($this->classOrInterface)) {


            // TODO merge ?


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
        } elseif (interface_exists($this->classOrInterface)) {
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

        foreach ($this->methods as $name => $method) {
            $expectCallCount = $method->getExpectCallCount();
            $m = $mock
                ->expects($expectCallCount !== null
                    ? new PHPUnit_Framework_MockObject_Matcher_InvokedCount($expectCallCount)
                    : new PHPUnit_Framework_MockObject_Matcher_AnyInvokedCount)
                ->method($name)
                ->willReturn($method->getWillReturn());
            $with = $method->getWith();
            if (is_array($with)) {
                call_user_func_array([$m, 'with'], $with);
            }
        }

        return $mock;
    }
}
