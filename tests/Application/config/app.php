<?php

return [
    'name' => 'Test name',
    'version' => 'Test version',
    'production' => false,
    'default-command' => Tests\Application\App\Commands\FakeDefaultCommand::class,
    'commands' => [
        Tests\Application\App\OtherCommands\FakeOtherCommand::class,
    ],
    'providers' => [
        Tests\Application\App\Providers\FakeServiceProvider::class,
    ],
];
