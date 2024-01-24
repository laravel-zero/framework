<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'fake:removed')]
class FakeRemovedCommand extends Command
{
    protected $name = 'fake:removed';

    public function handle()
    {
        $this->info('Foo bar');
    }
}
