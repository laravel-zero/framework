<?php

namespace Tests;

use LaravelZero\Framework\Commands\AbstractCommand;

class FakeExtraCommand extends AbstractCommand
{
    /**
     * The name of the console command.
     *
     * @var string
     */
    protected $name = 'fake:extra';

    /**
     * {@inheritdoc}
     */
    public function handle(): void
    {
        $this->info('Foo bar');
    }
}
