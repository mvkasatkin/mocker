<?php

namespace Example;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\MethodProphecy;
use Prophecy\Prophecy\ObjectProphecy;

class Dep1Test extends TestCase
{

    public function testA()
    {
        /** @var Dep1|ObjectProphecy $dep1 */
        $dep1 = $this->prophesize(Dep1::class);
        $dep1->
        /** @var MethodProphecy $m */
        $m = $dep1->set('a');
        $m->shouldBeCalled();
        $dep1->set('a')->shouldBeCalled();

    }
}
