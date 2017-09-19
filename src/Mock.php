<?php

namespace Mvkasatkin\mocker;

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
//            $object = $this->generator->getMock(
//                $this->type,
//                $this->methods,
//                $this->constructorArgs,
//                $this->mockClassName,
//                $this->originalConstructor,
//                $this->originalClone,
//                $this->autoload,
//                $this->cloneArguments,
//                $this->callOriginalMethods,
//                $this->proxyTarget,
//                $this->allowMockingUnknownTypes
//            );

            $mock = $this->generator->getMockForAbstractClass(
                $this->classOrInterface,
                $this->args ? $this->args : [],
                '',
                $this->args !== null,
                true,
                true,
                [],
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
                [],
                true
            );
        } else {
            throw new \Exception('Class or interface not found: ' . $this->classOrInterface);
        }
        return $mock;
    }
}
