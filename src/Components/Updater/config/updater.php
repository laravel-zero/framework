<?php

use LaravelZero\Framework\Components\Updater\Strategy\GithubStrategy;

return [

    /*
    |--------------------------------------------------------------------------
    | Self-updater Strategy
    |--------------------------------------------------------------------------
    |
    | Here you may specify which update strategy class you wish to use when
    | updating your application via the "self-update" command. This must
    | be a class that implements the StrategyInterface from Humbug.
    |
    */

    'strategy' => GithubStrategy::class,

    /*
    |--------------------------------------------------------------------------
    | Self-updater Phar Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify the name of the Phar file, as stored on GitHub or
    | GitLab. This can be configured if the Phar name is different to the
    | name of the Phar file running on the users' machine.
    |
    | Default: `null`
    |
    */

    'phar_name' => null,

];
