<?php

declare(strict_types=1);

use LaravelZero\Framework\Exceptions\ConsoleException;

it('sets console exception exit code', function () {
    $exception = new ConsoleException(404, 'Foo', ['bar' => 'etc']);

    assertEquals(404, $exception->getExitCode());
});

it('sets console exception headers', function () {
    $exception = new ConsoleException(404, 'Foo', ['bar' => 'etc']);

    assertEquals(['bar' => 'etc'], $exception->getHeaders());

    $exception->setHeaders(['foo' => 'zero']);

    assertEquals(['foo' => 'zero'], $exception->getHeaders());
});
