<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;

class FakeDefaultCommand extends Command
{
    protected $name = 'fake:default';

    public function handle()
    {
        $this->info('Foo bar');
    }
}
