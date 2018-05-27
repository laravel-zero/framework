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

use LogicException;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Console\Command as BaseCommand;

/**
 * This is the Laravel Zero Framework Abstract Command Implementation.
 */
abstract class Command extends BaseCommand
{
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
    public function handle(): void
    {
        throw new LogicException('You must override the handle() method in the concrete command class.');
    }

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
     * {@inheritdoc}
     */
    public function setLaravel($laravel)
    {
        parent::setLaravel($this->app = $laravel);
    }
}
