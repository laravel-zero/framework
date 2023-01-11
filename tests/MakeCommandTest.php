<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

afterEach(function () {
    File::delete(app_path('Commands'.DIRECTORY_SEPARATOR.'FooCommand.php'));
    File::delete(app_path('Commands/PublishedStubbedCommand.php'));
    File::delete(base_path('stubs/console.stub'));
});

it('can make a new command', function () {
    Artisan::call('make:command', ['name' => 'FooCommand']);

    $file = app_path('Commands'.DIRECTORY_SEPARATOR.'FooCommand.php');

    expect(File::exists($file))->toBeTrue()
        ->and(File::get($file))
        ->toContain('class FooCommand extends Command')
        ->toContain('use LaravelZero\Framework\Commands\Command;');
});

it('can make a new command from a published stub', function () {
    $consoleStubPath = base_path('stubs/console.stub');

    Artisan::call('stub:publish');
    expect($consoleStubPath)->toBeFile();

    File::put(
        $consoleStubPath,
        str_replace('extends Command', 'extends Command implements FooInterface', File::get($consoleStubPath))
    );

    Artisan::call('make:command', ['name' => 'PublishedStubbedCommand']);
    $file = app_path('Commands/PublishedStubbedCommand.php');

    expect($file)->toBeFile();
    expect(File::get($file))
        ->toContain('class PublishedStubbedCommand extends Command implements FooInterface')
        ->toContain('use LaravelZero\Framework\Commands\Command;');
});
