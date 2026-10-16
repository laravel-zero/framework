<?php

use App\Commands\FakeDefaultCommand;
use App\Commands\FakeHiddenPathCommand;
use App\Commands\FakeRemovedCommand;
use App\Commands\FakeRemovedLazyCommand;
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
        FakeHiddenPathCommand::class,
    ],
    'remove' => [
        FakeRemovedCommand::class,
        FakeRemovedLazyCommand::class,
    ],
];
