<?php

return [
    'default' => App\Commands\FakeDefaultCommand::class,
    'paths'   => [app_path('Commands')],
    'add'     => [
        App\OtherCommands\FakeOtherCommand::class,
    ],
    'hidden' => [
        App\HiddenCommands\FakeHiddenCommand::class,
    ],
    'remove' => [
        App\Commands\FakeRemovedCommand::class,
    ],
];
