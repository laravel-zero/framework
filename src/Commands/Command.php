<?php

declare(strict_types=1);

/**
 * This file is part of Laravel Zero.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace LaravelZero\Framework\Commands;

use strlen;
use str_repeat;
use LogicException;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Console\Command as BaseCommand;

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
     *
     * @throws \LogicException
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
    public function setLaravel($laravel): void
    {
        parent::setLaravel($this->app = $laravel);
    }

    /**
     * Displays a title.
     *
     * @param  string $title
     *
     * @return \LaravelZero\Framework\Commands\Command
     */
    public function title(string $title): Command
    {
        $size = strlen($title);
        $spaces = str_repeat(' ', $size);

        $this->output->newLine();
        $this->output->writeln("<bg=blue;fg=white>$spaces$spaces$spaces</>");
        $this->output->writeln("<bg=blue;fg=white>$spaces$title$spaces</>");
        $this->output->writeln("<bg=blue;fg=white>$spaces$spaces$spaces</>");
        $this->output->newLine();

        return $this;
    }
}
