<?php

namespace My;

class SomeClass
{
    protected $protectedProperty;
    private $privateProperty;

    public function publicMethod($a)
    {
        return $this->protectedMethod($a) . 'X';
    }

    protected function protectedMethod($a)
    {
        $this->protectedProperty = $a;
        return $this->privateMethod($a) . 'Y';
    }

    private function privateMethod($a)
    {
        $this->privateProperty = $a;
        return $a . 'Z';
    }

    public function checkMap($arg1 = null, $arg2 = null, $arg3 = null)
    {
        return [$arg1, $arg2, $arg3];
    }
}
