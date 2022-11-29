<?php

namespace Corals\Modules\Utility\Tag\Providers;

use Corals\Foundation\Providers\BaseUninstallModuleServiceProvider;
use Corals\Modules\Utility\Tag\database\migrations\CreateTagTables;
use Corals\Modules\Utility\Tag\database\seeds\UtilityTagDatabaseSeeder;

class UninstallModuleServiceProvider extends BaseUninstallModuleServiceProvider
{
    protected $migrations = [
        CreateTagTables::class,
    ];

    protected function providerBooted()
    {
        $this->dropSchema();

        $utilityTagDatabaseSeeder = new UtilityTagDatabaseSeeder();

        $utilityTagDatabaseSeeder->rollback();
    }
}
