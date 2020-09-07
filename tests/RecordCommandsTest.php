<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Support\Facades\Artisan;

it('can call a command without arguments', function () {
    Artisan::call('fake:foo');

    $this->assertCommandNotCalled('fake:foo', ['foo' => 'bar']);
    $this->assertCommandCalled('fake:foo');
});

it('can call a command with arguments', function () {
    Artisan::call('fake:foo', ['foo' => 'bar']);

    $this->assertCommandCalled('fake:foo', ['foo' => 'bar']);
    $this->assertCommandNotCalled('fake:foo');
});
