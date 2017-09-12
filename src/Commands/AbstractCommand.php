<?php

namespace LaravelZero\Framework\Commands;

use Illuminate\Contracts\Container\Container;
use Illuminate\Console\Command as BaseCommand;
use NunoMaduro\LaravelDesktopNotifier\Contracts\Notifier;
use NunoMaduro\LaravelDesktopNotifier\Contracts\Notification;

/**
 * This is the Zero Framework abstract command class.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
abstract class AbstractCommand extends BaseCommand
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
     * Gets the concrete implementation of the notifier. Then
     * creates a new notification and send it through the notifier.
     *
     * @param string $text
     * @param string $body
     * @param string|null $icon
     */
    public function notify(string $text, string $body, $icon = null): void
    {
        $notifier = $this->getContainer()
            ->make(Notifier::class);

        $notification = $this->getContainer()
            ->make(Notification::class)
            ->setTitle($text)
            ->setBody($body)
            ->setIcon($icon);

        $notifier->send($notification);
    }
}
