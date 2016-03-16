<?php

namespace DetailTest\Notification;

use PHPUnit_Framework_TestCase as TestCase;

use Detail\Notification;

class Test extends TestCase
{
    /**
     * @var Module
     */
    protected $sepp;

    protected function setUp()
    {
        $this->sepp = "sepp";
    }

    public function testSepp()
    {
        $name = $this->sepp;

        $this->assertEquals('sepp', $name);

    }
}
