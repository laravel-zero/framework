<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;

class FakeFooCommand extends Command
{
    /**
     * The name of the console command.
     *
     * @var string
     */
    protected $name = 'fake:foo';

    /**
     * {@inheritdoc}
     */
    public function handle(): void
    {
        $this->info('Foo bar');
    }
}
