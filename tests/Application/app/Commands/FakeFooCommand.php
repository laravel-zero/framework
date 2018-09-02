<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;

class FakeFooCommand extends Command
{
    protected $name = 'fake:foo';

    public function handle()
    {
        $this->info('Foo bar');
    }
}
