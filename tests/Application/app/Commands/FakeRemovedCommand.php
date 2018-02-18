<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;

class FakeRemovedCommand extends Command
{
    /**
     * The name of the console command.
     *
     * @var string
     */
    protected $name = 'fake:removed';

    /**
     * {@inheritdoc}
     */
    public function handle(): void
    {
        $this->info('Foo bar');
    }
}
