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

namespace LaravelZero\Framework\Components\Queue;

use Illuminate\Contracts\Config\Repository;
use LaravelZero\Framework\Components\AbstractComponentProvider;

use function class_exists;

/**
 * @internal
 */
final class Provider extends AbstractComponentProvider
{
    /**
     * {@inheritdoc}
     */
    public function isAvailable(): bool
    {
        return class_exists(\Illuminate\Bus\BusServiceProvider::class)
            && class_exists(\Illuminate\Queue\QueueServiceProvider::class)
            && file_exists($this->app->configPath('queue.php'))
            && $this->app['config']->get('queue.useDefaultProvider', true) === true;
    }

    /**
     * {@inheritdoc}
     */
    public function boot(): void
    {
        if ($this->app->environment() !== 'production') {
            $this->commands(
                [
                    \Illuminate\Queue\Console\TableCommand::class,
                    \Illuminate\Queue\Console\FailedTableCommand::class,
                    \Illuminate\Foundation\Console\JobMakeCommand::class,
                ]
            );
        }

        $this->commands(
            [
                \Illuminate\Queue\Console\WorkCommand::class,
                \Illuminate\Queue\Console\RetryCommand::class,
                \Illuminate\Queue\Console\ListenCommand::class,
                \Illuminate\Queue\Console\RestartCommand::class,
                \Illuminate\Queue\Console\ListFailedCommand::class,
                \Illuminate\Queue\Console\FlushFailedCommand::class,
                \Illuminate\Queue\Console\ForgetFailedCommand::class,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function register(): void
    {
        $this->app->register(\Illuminate\Bus\BusServiceProvider::class);
        $this->app->register(\Illuminate\Queue\QueueServiceProvider::class);

        $this->app->bind(
            \Illuminate\Queue\Worker::class,
            function ($app) {
                return $app['queue.worker'];
            }
        );

        $this->app->bind(
            \Illuminate\Queue\Listener::class,
            function ($app) {
                return $app['queue.listener'];
            }
        );

        /** @var Repository $config */
        $config = $this->app['config'];

        $config->set('queue.default', $config->get('queue.default') ?: 'default');
    }
}
