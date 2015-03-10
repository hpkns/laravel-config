<?php

use Mockery as m;
use Illuminate\Foundation\Application;
use Hpkns\Config\Commands\ConfigImport;
use League\Csv\Reader;


class ConfigImportpTest extends PHPUnit_Framework_TestCase {

    public function tearDown()
    {
        m::close();
    }

    public function testConfigImportIsInstanciable()
    {
        $this->assertInstanceOf('Hpkns\Config\Commands\ConfigImport', new ConfigImport);
    }

    public function testConfigImportGetCSV()
    {
        $command = new ConfigImport;
        $csv = $command->getCSV(Hpkns\Config\Commands\base_path() . '/seeds/translations.scv');
        $this->assertInstanceOf('League\Csv\Reader', $csv);
    }

    public function testGetArguments()
    {
        $command = new ConfigImport;
        $this->assertInternalType('array', $command->getArguments());
    }

}
