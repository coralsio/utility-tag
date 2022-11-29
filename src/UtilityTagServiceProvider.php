<?php

namespace Corals\Modules\Utility\Tag;

use Corals\Modules\Utility\Tag\Providers\UtilityAuthServiceProvider;
use Corals\Modules\Utility\Tag\Providers\UtilityRouteServiceProvider;
use Corals\Modules\Utility\Tag\Facades\Tag;
use Corals\Modules\Utility\Tag\Models\Tag as TagModel;
use Corals\Settings\Facades\Modules;
use Corals\Settings\Facades\Settings;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class UtilityTagServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'utility-tag');
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'utility-tag');

        $this->mergeConfigFrom(
            __DIR__ . '/config/utility-tag.php',
            'utility-tag'
        );
        $this->publishes([
            __DIR__ . '/config/utility-tag.php' => config_path('utility-tag.php'),
            __DIR__ . '/resources/views' => resource_path('resources/views/vendor/utility-tag'),
        ]);

        $this->registerMorphMaps();
        $this->registerModulesPackages();
    }

    public function register()
    {
        $this->app->register(UtilityAuthServiceProvider::class);
        $this->app->register(UtilityRouteServiceProvider::class);

        $this->app->booted(function () {
            $loader = AliasLoader::getInstance();
            $loader->alias('Tag', Tag::class);
        });
    }

    protected function registerMorphMaps()
    {
        Relation::morphMap([
            'UtilityTag' => TagModel::class,
        ]);
    }

    protected function registerModulesPackages()
    {
        Modules::addModulesPackages('corals/utility-tag');
    }
}
