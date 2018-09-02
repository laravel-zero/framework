<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;

class FakeRemovedCommand extends Command
{
    protected $name = 'fake:removed';

    public function handle()
    {
        $this->info('Foo bar');
    }
}
