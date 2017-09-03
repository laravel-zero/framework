<?php

use LaravelZero\Framework\Commands;

return [
    'app' => [
        'commands' => [
            Commands\App\Builder::class,
            Commands\App\Renamer::class,
            Commands\Component\Installer::class,
        ],
    ],
];
