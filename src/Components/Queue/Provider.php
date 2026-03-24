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

use Illuminate\Bus\BusServiceProvider;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Foundation\Console\JobMakeCommand;
use Illuminate\Queue\Console\FailedTableCommand;
use Illuminate\Queue\Console\FlushFailedCommand;
use Illuminate\Queue\Console\ForgetFailedCommand;
use Illuminate\Queue\Console\ListenCommand;
use Illuminate\Queue\Console\ListFailedCommand;
use Illuminate\Queue\Console\RestartCommand;
use Illuminate\Queue\Console\RetryCommand;
use Illuminate\Queue\Console\TableCommand;
use Illuminate\Queue\Console\WorkCommand;
use Illuminate\Queue\Listener;
use Illuminate\Queue\QueueServiceProvider;
use Illuminate\Queue\Worker;
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
        return class_exists(BusServiceProvider::class)
            && class_exists(QueueServiceProvider::class)
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
                    TableCommand::class,
                    FailedTableCommand::class,
                    JobMakeCommand::class,
                ]
            );
        }

        $this->commands(
            [
                WorkCommand::class,
                RetryCommand::class,
                ListenCommand::class,
                RestartCommand::class,
                ListFailedCommand::class,
                FlushFailedCommand::class,
                ForgetFailedCommand::class,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function register(): void
    {
        $this->app->register(BusServiceProvider::class);
        $this->app->register(QueueServiceProvider::class);

        $this->app->bind(
            Worker::class,
            function ($app) {
                return $app['queue.worker'];
            }
        );

        $this->app->bind(
            Listener::class,
            function ($app) {
                return $app['queue.listener'];
            }
        );

        /** @var Repository $config */
        $config = $this->app['config'];

        $config->set('queue.default', $config->get('queue.default') ?: 'default');
    }
}
