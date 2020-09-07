<?php

declare(strict_types=1);

use App\Commands\FakeDefaultCommand;
use App\Commands\FakeFooCommand;
use App\HiddenCommands\FakeHiddenCommand;
use App\OtherCommands\FakeOtherCommand;
use Illuminate\Support\Facades\Artisan;

it('can access the application configuration', function () {
    expect(Artisan::getName())->toBe('Application');
    expect($this->app->version())->toBe('Test version');
    expect($this->app->environment())->toEqual('development');
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
        expect($appCommands)->toContain($command);
    }
});

it('can hide application commands', function () {
    expect(Artisan::all()['fake:hidden']->isHidden())->toBeTrue();
});

it('can remove application commands', function () {
    expect(Artisan::all())->not->toHaveKey('fake:removed');
});
