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

use Humbug\SelfUpdate\Updater as PharUpdater;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use LaravelZero\Framework\Components\AbstractComponentProvider;
use LaravelZero\Framework\Components\Updater\Strategy\GithubStrategy;
use LaravelZero\Framework\Components\Updater\Strategy\StrategyInterface;
use LaravelZero\Framework\Providers\Build\Build;
use Phar;

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
        return class_exists(PharUpdater::class);
    }

    /**
     * {@inheritdoc}
     */
    public function boot(): void
    {
        $build = $this->app->make(Build::class);

        if (! $this->app->environment('production')) {
            $this->publishes([
                __DIR__.'/config/updater.php' => $this->app->configPath('updater.php'),
            ]);
        }

        $this->mergeConfigFrom(__DIR__.'/config/updater.php', 'updater');

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
        $build = $this->app->make(Build::class);

        if ($build->isRunning() && $this->app->environment('production')) {
            $this->app->singleton(Updater::class, function () use ($build) {
                $updater = new PharUpdater($build->getPath(), false, PharUpdater::STRATEGY_GITHUB);

                $composer = json_decode(file_get_contents($this->app->basePath('composer.json')), true);

                /** @var ConfigRepository $config */
                $config = $this->app->make(ConfigRepository::class);

                $strategyClass = $config->get('updater.strategy', GithubStrategy::class);

                $updater->setStrategyObject($strategy = $this->app->make($strategyClass));

                if ($strategy instanceof StrategyInterface) {
                    assert(isset($composer['name']), 'Package name has not been set in Composer');

                    $strategy->setPackageName($composer['name']);
                }

                if (method_exists($strategy, 'setPharName')) {
                    $strategy->setPharName($config->get('updater.phar_name') ?? basename(Phar::running()));
                }

                if (method_exists($strategy, 'setCurrentLocalVersion')) {
                    $strategy->setCurrentLocalVersion($config->get('app.version'));
                }

                return new Updater($updater);
            });
        }
    }
}
