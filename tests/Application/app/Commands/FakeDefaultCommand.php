<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'fake:default')]
class FakeDefaultCommand extends Command
{
    protected $name = 'fake:default';

    public function handle()
    {
        $this->info('Foo bar');
    }
}
