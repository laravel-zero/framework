<?php

namespace App\HiddenCommands;

use LaravelZero\Framework\Commands\Command;

class FakeHiddenCommand extends Command
{
    protected $name = 'fake:hidden';

    public function handle()
    {
        $this->info('Foo hidden');
    }
}
