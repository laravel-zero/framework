<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Migrations Commands
    |--------------------------------------------------------------------------
    |
    | Migrations are like version control for your database, allowing your
    | team to easily modify and share the application's database schema.
    | Set this value as true see all the available migration commands.
    */
    'with-migrations' => true,

    /*
    |--------------------------------------------------------------------------
    | Seeds Commands
    |--------------------------------------------------------------------------
    |
    | Here you may specify if you want to use Seeds Commands on your console
    | application. If you set this value as true, you will be able to use
    | commands that seed your database with default data or test data.
    |
    */
    'with-seeds' => true,

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
