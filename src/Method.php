<?php

namespace Mvkasatkin\mocker;

class Method
{
    protected $name;
    protected $expectCallCount;
    protected $willReturn;
    protected $willReturnMap = [];
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
        if ($args && !is_array($args)) {
            $args = [$args];
        }
        $this->args = $args;
        return $this;
    }

    public function returns($value)
    {
        $this->willReturn = $value;
        return $this;
    }

    /**
     * @param array $map
     * map example:
     * ```
     * [
     *      [$arg1, $arg2, $arg3, $value1],
     *      [$arg4, $arg5, $value2],
     *      [$arg6, $value3],
     *      [$value4],
     * ]
     * ```
     *
     * @return $this
     */
    public function returnsMap(array $map)
    {
        $this->willReturnMap = $map;
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

    public function getWillReturnMap(): array
    {
        return $this->willReturnMap;
    }

    public function getWith()
    {
        return $this->args;
    }
}
