<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;

class FakeHiddenPathCommand extends Command
{
    protected $name = 'fake:hidden-path';

    public function handle()
    {
        $this->info('Foo hidden path');
    }
}
