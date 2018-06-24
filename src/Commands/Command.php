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

use LogicException;
use function strlen;
use function str_repeat;
use function func_get_args;
use NunoMaduro\LaravelConsoleMenu\Menu;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Console\Command as BaseCommand;

abstract class Command extends BaseCommand
{
    /**
     * Holds an instance of the app, if any.
     *
     * @var \LaravelZero\Framework\Application
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

    /*
     * Returns a menu builder.
     *
     * @param  string $title
     * @param  array $options
     *
     * @return \NunoMaduro\LaravelConsoleMenu\Menu
     */
    public function menu(string $title, array $options = []): Menu
    {
        return $this->__call('menu', func_get_args());
    }

    /*
     * Performs the given task, outputs and
     * returns the result.
     *
     * @param  string $title
     * @param  callable|null $task
     *
     * @return bool With the result of the task.
     */
    public function task(string $title = '', $task = null): bool
    {
        return $this->__call('task', func_get_args());
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
