<?php

namespace App\OtherCommands;

use LaravelZero\Framework\Commands\Command;

class FakeOtherCommand extends Command
{
    protected $name = 'fake:other';

    public function handle()
    {
        $this->info('Foo bar');
    }
}
