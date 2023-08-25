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

use Illuminate\Console\Command as BaseCommand;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Application;
use LaravelZero\Framework\Providers\CommandRecorder\CommandRecorderRepository;

use function func_get_args;
use function str_repeat;
use function strlen;

abstract class Command extends BaseCommand
{
    /**
     * Holds an instance of the app, if any.
     *
     * @var Application
     */
    protected $app;

    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule)
    {
    }

    /**
     * @param  Application  $laravel
     */
    public function setLaravel($laravel): void
    {
        parent::setLaravel($this->app = $laravel);
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

    /**
     * {@inheritdoc}
     */
    public function call($command, array $arguments = [])
    {
        resolve(CommandRecorderRepository::class)->create($command, $arguments);

        return parent::call($command, $arguments);
    }

    /**
     * {@inheritdoc}
     */
    public function callSilent($command, array $arguments = [])
    {
        resolve(CommandRecorderRepository::class)->create($command, $arguments, CommandRecorderRepository::MODE_SILENT);

        return parent::callSilent($command, $arguments);
    }

    /**
     * {@inheritdoc}
     *
     * @see {https://github.com/laravel/framework/pull/27005}
     */
    public function setHidden(bool $hidden = true): static
    {
        parent::setHidden($this->hidden = $hidden);

        return $this;
    }
}
