<?php

use App\Providers\FakeServiceProvider;

return [
    'name' => 'Application',
    'version' => 'Test version',
    'env' => 'production',
    'providers' => [
        FakeServiceProvider::class,
    ],
];
