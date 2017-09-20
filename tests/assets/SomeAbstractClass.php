<?php

namespace My;

abstract class SomeAbstractClass
{

    protected $protectedProperty;
    private $privateProperty;

    abstract public function abstractMethod($a);

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

}