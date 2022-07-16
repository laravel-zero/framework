<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'fake:foo')]
class FakeFooCommand extends Command
{
    protected $signature = 'fake:foo {foo? : The bar}';

    public function handle()
    {
        $this->info('Foo bar');
    }
}
