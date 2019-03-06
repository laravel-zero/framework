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

namespace LaravelZero\Framework\Components\Updater;

use function class_exists;
use Humbug\SelfUpdate\Updater as PharUpdater;
use LaravelZero\Framework\Components\AbstractComponentProvider;

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
        return class_exists(\Humbug\SelfUpdate\Updater::class) && file_exists($this->app->configPath('updater.php'));
    }

    /**
     * {@inheritdoc}
     */
    public function boot(): void
    {
        if ($this->app->environment() === 'production') {
            $this->commands([
                UpdateCommand::class,
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function register(): void
    {
        $config = $this->app['config'];

        if ($config->get('app.production', false)) {

            $this->app->singleton(PharUpdater::class, function ($app) {
                return new PharUpdater();
            });

            $this->app->singleton(Updater::class, function ($app) {
                return new Updater(
                    $app['config']->get('app.name'),
                    $app['config']->get('updater', []),
                    new PharUpdater
                );
            });
        }


    }
}
