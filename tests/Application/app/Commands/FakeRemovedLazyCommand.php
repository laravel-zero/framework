<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'fake:removed-lazy')]
class FakeRemovedLazyCommand extends Command
{
    public function handle()
    {
        $this->info('Foo removed lazy');
    }
}
