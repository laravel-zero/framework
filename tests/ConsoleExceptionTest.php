<?php

declare(strict_types=1);

use LaravelZero\Framework\Exceptions\ConsoleException;

it('sets console exception exit code', function () {
    $exception = new ConsoleException(404, 'Foo', ['bar' => 'etc']);

    expect($exception->getExitCode())->toEqual(404);
});

it('sets console exception headers', function () {
    $exception = new ConsoleException(404, 'Foo', ['bar' => 'etc']);

    expect($exception->getHeaders())->toEqual(['bar' => 'etc']);

    $exception->setHeaders(['foo' => 'zero']);

    expect($exception->getHeaders())->toEqual(['foo' => 'zero']);
});
