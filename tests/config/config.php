<?php

return [
    'app' => [

        'name' => 'Test name',

        'version' => 'Test version',

        'production' => false,

        'default-command' => Tests\FakeDefaultCommand::class,

        'commands' => [
            Tests\FakeExtraCommand::class,
        ],

        'providers' => [
            Tests\FakeServiceProvider::class,
        ],
    ],

    'database' => [
        'connections' => [
            'default' => [
                'driver' => 'sqlite',
                'database' => __DIR__.'/../database/database.sqlite',
            ],
        ],
    ],

    'cache' => [
        'default' => 'array',
        'stores' => [
            'array' => [
                'driver' => 'array',
            ],
        ],
    ],
];
