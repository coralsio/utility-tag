<?php

return [
    'models' => [
        'tag' => [
            'presenter' => \Corals\Modules\Utility\Tag\Transformers\TagPresenter::class,
            'resource_url' => 'utilities/tags',
            'translatable' => ['name'],
        ],
    ],
];
