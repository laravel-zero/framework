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
use NunoMaduro\LaravelDesktopNotifier\Contracts\Notifier;
use NunoMaduro\LaravelDesktopNotifier\Contracts\Notification;

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
     * {@innerdoc}
     */
    public function setLaravel($laravel)
    {
        parent::setLaravel($this->app = $laravel);
    }

    /**
     * Gets the concrete implementation of the notifier. Then
     * creates a new notification and send it through the notifier.
     *
     * @param string $title
     * @param string $body
     * @param string|null $icon
     *
     * @return void
     */
    public function notify(string $title, string $body, $icon = null): void
    {
        $notifier = $this->laravel->make(Notifier::class);

        $notification = $this->laravel->make(Notification::class)->setTitle($title)->setBody($body)->setIcon(
            $icon
        );

        $notifier->send($notification);
    }

    /**
     * Performs the given task and outputs the result.
     *
     * @param string $title
     * @param callable $task
     *
     * @return bool
     */
    public function task(string $title, callable $task): bool
    {
        return tap(
            $task(),
            function ($result) use ($title) {
                $this->output->writeln(
                    "$title: " . ($result ? '<info>✔</info>' : '<error>failed</error>')
                );
            }
        );
    }
}
