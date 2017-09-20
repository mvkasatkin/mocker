<?php

namespace Test;

use Mvkasatkin\mocker\Mocker;
use PHPUnit\Framework\TestCase;

class MockerTestCase extends TestCase
{

    public function setUp()
    {
        parent::setUp();
        Mocker::init($this);
    }

}