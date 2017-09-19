<?php

use Mvkasatkin\mocker\Mocker;

class MockingTest extends \PHPUnit\Framework\TestCase
{

    public function setUp()
    {
        parent::setUp();
        Mocker::init($this);
    }

    public function testConstructor()
    {
//        $this->createMock()
//        $this->getMockForAbstractClass()
        $res = Mocker::mock(\My\ResourceA::class);
        $service = Mocker::mock(\My\ServiceA::class, [$res], []);
        $this->assertInstanceOf(\My\ServiceA::class, $service);
    }

    public function testUpdate()
    {
        $res = Mocker::mock(\My\ResourceA::class, null, [
            Mocker::method('get', 1)->returns([11,22,33]),
            Mocker::method('set', 1),
        ]);

//        $res = Mocker::mock(\My\ResourceA::class, null)
//            ->set(Mocker::method('get', 1)->returns([11,22,33]))
//            ->set(Mocker::method('set', 1))
//            ->create();

        $service = new \My\ServiceA($res);
        $this->assertTrue($service->update(11));
    }

}