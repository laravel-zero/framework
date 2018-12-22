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

use LaravelZero\Framework\Providers\CommandRecorder\CommandRecorder;
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
     * @var CommandRecorder
     */
    protected $recorder;

    public function __construct()
    {
        parent::__construct();

        $this->recorder = app(CommandRecorder::class);
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    public function schedule(Schedule $schedule)
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
     * Returns a menu builder.
     */
    public function menu(string $title, array $options = []): Menu
    {
        return $this->__call('menu', func_get_args());
    }

    /**
     * Performs the given task, outputs and
     * returns the result.
     */
    public function task(string $title = '', $task = null): bool
    {
        return $this->__call('task', func_get_args());
    }

    /*
     * Displays the given string as title.
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

    public function call($command, array $arguments = [])
    {
        $this->record($command, $arguments);

        return parent::call($command, $arguments);
    }

    public function callSilent($command, array $arguments = [])
    {
        $this->record($command, $arguments, CommandRecorder::MODE_SILENT);

        return parent::callSilent($command, $arguments);
    }

    protected function record($command, $arguments, $mode = CommandRecorder::MODE_DEFAULT)
    {
        $this->recorder->record($command, $arguments, $mode);
    }
}
