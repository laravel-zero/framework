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
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
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
        $notifier = $this->getContainer()
            ->make(Notifier::class);

        $notification = $this->getContainer()
            ->make(Notification::class)
            ->setTitle($title)
            ->setBody($body)
            ->setIcon($icon);

        $notifier->send($notification);
    }
}
