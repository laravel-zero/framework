<?php

namespace Tests;

use LaravelZero\Framework\Commands\AbstractCommand;

class FakeDefaultCommand extends AbstractCommand
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
