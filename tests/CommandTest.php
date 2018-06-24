<?php

declare(strict_types=1);

namespace Tests;

use LogicException;
use Illuminate\Support\Facades\Artisan;
use LaravelZero\Framework\Commands\Command;

final class CommandTest extends TestCase
{
    public function testThatHandleMethodMustBeOverwritten(): void
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
