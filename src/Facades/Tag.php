<?php

namespace Corals\Modules\Utility\Tag\Facades;

use Illuminate\Support\Facades\Facade;

class Tag extends Facade
{
    /**
     * @return mixed
     */
    protected static function getFacadeAccessor()
    {
        return \Corals\Modules\Utility\Tag\Classes\TagManager::class;
    }
}
