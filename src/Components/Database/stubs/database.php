<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */
    'connections' => [
        'default' => [
            'driver' => 'sqlite',
            'database' => database_path('database.sqlite'),
        ],
    ],

];
