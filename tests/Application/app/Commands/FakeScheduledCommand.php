<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class FakeScheduledCommand extends Command
{
    protected $name = 'fake:scheduled';

    public function handle()
    {
        $this->info('Foo scheduled');
    }

    public function schedule(Schedule $schedule): void
    {
        $schedule->command(static::class)->everyMinute();
    }
}
