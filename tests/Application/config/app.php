<?php

return [
    'name' => 'Test name',
    'version' => 'Test version',
    'production' => false,
    'default-command' => Tests\Application\FakeDefaultCommand::class,
    'commands' => [
        Tests\Application\FakeExtraCommand::class,
    ],
    'providers' => [
        Tests\Application\FakeServiceProvider::class,
    ],
];
