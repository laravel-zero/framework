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

];
