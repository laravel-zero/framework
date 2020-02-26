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
use LaravelZero\Framework\Providers\CommandRecorder\CommandRecorderRepository;
use function func_get_args;
use function str_repeat;
use function strlen;

abstract class Command extends BaseCommand
{
    /**
     * Holds an instance of the app, if any.
     *
     * @var \LaravelZero\Framework\Application
     */
    protected $app;

    /**
     * Define the command's schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
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
    public function setHidden($hidden)
    {
        parent::setHidden($this->hidden = $hidden);

        return $this;
    }

    /**
     * @param int $count
     * @return Command
     */
    public function newLine(int $count = 1)
    {
        $this->output->newLine($count);

        return $this;
    }

    /**
     * @param string|string[] $message
     * @return Command
     */
    public function successBlock($message)
    {
        $this->output->success($message);

        return $this;
    }

    /**
     * @param array $elements
     * @return $this
     */
    public function listing(array $elements)
    {
        $this->output->listing($elements);

        return $this;
    }

    /**
     * @param string|string[] $message
     * @return $this
     */
    public function write($message)
    {
        $this->output->write($message);

        return $this;
    }

    /**
     * @param string|string[] $message
     */
    public function errorBlock($message)
    {
        $this->output->error($message);
    }

    /**
     * @return self
     */
    public function progressStart()
    {
        $this->output->progressStart();

        return $this;
    }

    /**
     * @param int $step
     * @return self
     */
    public function progressAdvance(int $step = 1)
    {
        $this->output->progressAdvance($step);

        return $this;
    }

    public function progressFinish()
    {
        $this->output->progressFinish();

        return $this;
    }


}
