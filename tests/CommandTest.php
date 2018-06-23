<?php

declare(strict_types=1);

namespace Tests;

use LogicException;
use Illuminate\Support\Facades\Artisan;
use LaravelZero\Framework\Commands\Command;

final class CommandTest extends TestCase
{
    /** @test */
    public function it_forces_the_override_of_the_handle_method(): void
    {
        $command = new class() extends Command
        {
            protected $name = 'handle:test';
        };

        Artisan::registerCommand($command);
        $this->expectException(LogicException::class);
        Artisan::call('handle:test');
    }
}