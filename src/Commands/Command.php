<?php

namespace LaravelZero\Framework\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Container\Container;
use Illuminate\Console\Command as BaseCommand;
use NunoMaduro\LaravelDesktopNotifier\Contracts\Notifier;
use NunoMaduro\LaravelDesktopNotifier\Contracts\Notification;

/**
 * This is the Laravel Zero Framework abstract command class.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
abstract class Command extends BaseCommand
{
    /**
     * Holds an instance of the app.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * Create a new console command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->app = $this->getLaravel();
    }

    /**
     * Execute the console command. Here goes the command
     * code.
     *
     * @return void
     */
    abstract public function handle(): void;

    /**
     * Returns the application container.
     *
     * @return \Illuminate\Contracts\Container\Container
     */
    public function getContainer(): Container
    {
        return $this->getLaravel();
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
     * Gets the concrete implementation of the notifier. Then
     * creates a new notification and send it through the notifier.
     *
     * @param string $title
     * @param string $body
     * @param string|null $icon
     */
    public function notify(string $title, string $body, $icon = null): void
    {
        $notifier = $this->getContainer()->make(Notifier::class);

        $notification = $this->getContainer()->make(Notification::class)->setTitle($title)->setBody($body)->setIcon(
                $icon
            );

        $notifier->send($notification);
    }

    /**
     * Performs the given task and outputs
     * the result.
     *
     * @param string $title
     * @param callable $task
     *
     * @return bool
     */
    public function task(string $title, callable $task)
    {
        return tap(
            $task(),
            function ($result) use ($title) {
                $this->output->writeln(
                    "$title: ".($result ? '<info>✔</info>' : '<error>failed</error>')
                );
            }
        );
    }
}
