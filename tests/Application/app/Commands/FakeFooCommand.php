<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;

class FakeFooCommand extends Command
{
    protected $signature = 'fake:foo {foo? : The bar}';

    public function handle()
    {
        $this->info('Foo bar');
    }
}
