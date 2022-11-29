<?php

namespace Corals\Modules\Utility\Tag\Providers;

use Corals\Foundation\Providers\BaseInstallModuleServiceProvider;
use Corals\Modules\Utility\Tag\database\migrations\CreateTagTables;
use Corals\Modules\Utility\Tag\database\seeds\UtilityTagDatabaseSeeder;

class InstallModuleServiceProvider extends BaseInstallModuleServiceProvider
{
    protected $migrations = [
        CreateTagTables::class,
    ];

    protected function providerBooted()
    {
        $this->createSchema();

        $utilityTagDatabaseSeeder = new UtilityTagDatabaseSeeder();

        $utilityTagDatabaseSeeder->run();
    }
}
