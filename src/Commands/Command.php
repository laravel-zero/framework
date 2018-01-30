<?php

/**
 * This file is part of Laravel Zero.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace LaravelZero\Framework\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Console\Command as BaseCommand;
use NunoMaduro\LaravelDesktopNotifier\Notifications;

/**
 * This is the Laravel Zero Framework Abstract Command Implementation.
 */
abstract class Command extends BaseCommand
{
    use Notifications;

    /**
     * Holds an instance of the app, if any.
     *
     * @var \Illuminate\Contracts\Foundation\Application|null
     */
    protected $app;

    /**
     * Execute the console command.
     *
     * @return void
     */
    abstract public function handle(): void;

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
    }

    /**
     * {@innerdoc}.
     */
    public function setLaravel($laravel)
    {
        parent::setLaravel($this->app = $laravel);
    }
}
