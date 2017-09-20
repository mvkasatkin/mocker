<?php

namespace Mvkasatkin\mocker;

class Method
{

    protected $name;
    protected $expectCallCount = null;
    protected $willReturn;
    protected $args;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function expect(int $callCount = null)
    {
        $this->expectCallCount = $callCount;
        return $this;
    }

    public function with($args)
    {
        $this->args = $args;
        return $this;
    }

    public function returns($value)
    {
        $this->willReturn = $value;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getExpectCallCount()
    {
        return $this->expectCallCount;
    }

    public function getWillReturn()
    {
        return $this->willReturn;
    }

    public function getWith()
    {
        return $this->args;
    }

}
