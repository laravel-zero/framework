<?php

use App\Commands\FakeDefaultCommand;
use App\Commands\FakeRemovedCommand;
use App\HiddenCommands\FakeHiddenCommand;
use App\OtherCommands\FakeOtherCommand;

return [
    'default' => FakeDefaultCommand::class,
    'paths' => [app_path('Commands')],
    'add' => [
        FakeOtherCommand::class,
    ],
    'hidden' => [
        FakeHiddenCommand::class,
    ],
    'remove' => [
        FakeRemovedCommand::class,
    ],
];
