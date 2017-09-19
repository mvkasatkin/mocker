<?php

namespace Mvkasatkin\mocker;

class Method
{

    const ANY = '__any';

    protected $name;
    protected $expectCallCount = null;
    protected $willReturn = self::ANY;
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

    public function with(...$args)
    {
        $this->args = $args;
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
