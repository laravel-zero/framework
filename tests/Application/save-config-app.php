<?php

use App\Providers\FakeServiceProvider;

return [
    'name' => 'Application',
    'version' => 'Test version',
    'env' => 'development',
    'providers' => [
        FakeServiceProvider::class,
    ],
];
