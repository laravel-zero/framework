<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Support\Facades\Artisan;

final class RecordCommandsTest extends TestCase
{
    public function testCommandCalledWithoutArguments()
    {
        Artisan::call('fake:foo');

        $this->assertCommandNotCalled('fake:foo', ['foo' => 'bar']);
        $this->assertCommandCalled('fake:foo');
    }

    public function testCommandCalledWithArguments()
    {
        Artisan::call('fake:foo', ['foo' => 'bar']);

        $this->assertCommandCalled('fake:foo', ['foo' => 'bar']);
        $this->assertCommandNotCalled('fake:foo');
    }
}
