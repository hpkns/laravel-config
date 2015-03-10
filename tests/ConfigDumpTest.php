<?php

use Mockery as m;
use Illuminate\Foundation\Application;

class ConfigDumpTest extends PHPUnit_Framework_TestCase {

    public function tearDown()
    {
        m::close();
    }

    public function testNoop()
    {

    }


}
