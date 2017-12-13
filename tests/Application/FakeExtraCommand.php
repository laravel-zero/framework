<?php

namespace Tests\Application;

use LaravelZero\Framework\Commands\Command;

class FakeExtraCommand extends Command
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
