<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use LaravelZero\Framework\Contracts\Providers\ComposerContract;

it('installs the required packages', function () {
    $composerMock = $this->createMock(ComposerContract::class);

    $composerMock->expects($this->once())
        ->method('remove')
        ->with('phpunit/phpunit');

    $composerMock->expects($this->once())
        ->method('require')
        ->with('pestphp/pest');

    $this->app->instance(ComposerContract::class, $composerMock);

    Artisan::call('app:install', ['component' => 'pest']);
});

it('adds the components commands to the application', function () {
    $commands = collect(Artisan::all())
        ->map(
            function ($command) {
                return get_class($command);
            }
        )
        ->flip();

    collect(
        [
            \Pest\Laravel\Commands\PestDatasetCommand::class,
            \Pest\Laravel\Commands\PestInstallCommand::class,
            \Pest\Laravel\Commands\PestTestCommand::class,
        ]
    )->map(
        function ($commandClass) use ($commands) {
            expect($commands)->toHaveKey($commandClass);
        }
    );
});
