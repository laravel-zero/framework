<?php

namespace App\HiddenCommands;

use LaravelZero\Framework\Commands\Command;

class FakeHiddenCommand extends Command
{
    /**
     * The name of the console command.
     *
     * @var string
     */
    protected $name = 'fake:hidden';

    /**
     * {@inheritdoc}
     */
    public function handle(): void
    {
        $this->info('Foo hidden');
    }
}
