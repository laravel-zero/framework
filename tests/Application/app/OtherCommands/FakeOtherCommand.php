<?php

namespace Tests\Application\App\OtherCommands;

use LaravelZero\Framework\Commands\Command;

class FakeOtherCommand extends Command
{
    /**
     * The name of the console command.
     *
     * @var string
     */
    protected $name = 'fake:other';

    /**
     * {@inheritdoc}
     */
    public function handle(): void
    {
        $this->info('Foo bar');
    }
}
