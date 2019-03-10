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
use LaravelZero\Framework\Providers\Build\Build;
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
        return class_exists(\Humbug\SelfUpdate\Updater::class);
    }

    /**
     * {@inheritdoc}
     */
    public function boot(): void
    {
        $build = $this->app->make(Build::class);

        if ($build->isRunning() && $this->app->environment() === 'production') {
            $this->commands([
                SelfUpdateCommand::class,
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function register(): void
    {
        $config = $this->app['config'];

        $build = $this->app->make(Build::class);

        if ($build->isRunning() && $config->get('app.production', false)) {
            $this->app->singleton(Updater::class, function () use ($build) {
                $updater = new PharUpdater($build->getPath(), false, PharUpdater::STRATEGY_GITHUB);

                $composer = json_decode(file_get_contents(base_path('composer.json')), true);
                $name = $composer['name'];

                $updater->setStrategyObject(new GithubStrategy);

                $updater->getStrategy()->setPackageName($name);

                $updater->getStrategy()->setCurrentLocalVersion(config('app.version'));

                return new Updater($updater);
            });
        }
    }
}
