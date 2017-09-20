<?php

namespace My;

abstract class SomeAbstractClass
{

    abstract public function abstractMethod($a);

    public function publicMethod($a)
    {
        return $this->protectedMethod($a) . 'X';
    }

    protected function protectedMethod($a)
    {
        return $this->privateMethod($a) . 'Y';
    }

    private function privateMethod($a)
    {
        return $a . 'Z';
    }

}