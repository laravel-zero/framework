<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'fake:environmentValue')]
class FakeEnvironmentValueCommand extends Command
{
    protected $name = 'fake:environmentValue';

    public function handle()
    {
        $this->info(env('CONSUMER_KEY', 'NOTFOUND'));
    }
}
