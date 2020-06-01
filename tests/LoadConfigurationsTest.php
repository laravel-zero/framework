<?php

declare(strict_types=1);

use App\Commands\FakeDefaultCommand;
use App\Commands\FakeFooCommand;
use App\HiddenCommands\FakeHiddenCommand;
use App\OtherCommands\FakeOtherCommand;
use Illuminate\Support\Facades\Artisan;

it('can access the application configuration', function () {
    assertSame('Application', Artisan::getName());
    assertSame('Test version', $this->app->version());
    assertEquals($this->app->environment(), 'development');
});

it('can add commands to the application', function () {
    $commands = [
        FakeDefaultCommand::class,
        FakeFooCommand::class,
        FakeOtherCommand::class,
        FakeHiddenCommand::class,
    ];

    $appCommands = collect(Artisan::all())
        ->map(
            function ($command) {
                return get_class($command);
            }
        )
        ->toArray();

    foreach ($commands as $command) {
        assertContains($command, $appCommands);
    }
});

it('can hide application commands', function () {
    assertTrue(Artisan::all()['fake:hidden']->isHidden());
});

it('can remove application commands', function () {
    assertArrayNotHasKey('fake:removed', Artisan::all());
});
