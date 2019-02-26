<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;

class FakeEnvironmentValueCommand extends Command
{
    protected $name = 'fake:environmentValue';

    public function handle()
    {
        $this->info(env('CONSUMER_KEY', 'NOTFOUND'));
    }
}
