<?php

namespace My;

class SomeClass
{

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