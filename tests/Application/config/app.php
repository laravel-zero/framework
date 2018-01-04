<?php

return [
    'name' => 'Test name',
    'version' => 'Test version',
    'production' => false,
    'default-command' => App\Commands\FakeDefaultCommand::class,
    'commands' => [
        App\OtherCommands\FakeOtherCommand::class,
    ],
    'providers' => [
        App\Providers\FakeServiceProvider::class,
    ],
];
