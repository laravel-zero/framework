<?php

namespace Tests;

use LaravelZero\Framework\Commands\Command;

class FakeDefaultCommand extends Command
{
    /**
     * The name of the console command.
     *
     * @var string
     */
    protected $name = 'fake:default';

    /**
     * {@inheritdoc}
     */
    public function handle(): void
    {
        $this->info('Foo bar');
    }
}
