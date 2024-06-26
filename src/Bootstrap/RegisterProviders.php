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

namespace LaravelZero\Framework\Bootstrap;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Bootstrap\RegisterProviders as BaseRegisterProviders;
use LaravelZero\Framework\Application;
use LaravelZero\Framework\Components;
use LaravelZero\Framework\Contracts\BootstrapperContract;
use LaravelZero\Framework\Kernel as ConsoleKernel;
use LaravelZero\Framework\Providers;
use LaravelZero\Framework\Providers\Collision\CollisionServiceProvider;
use LaravelZero\Framework\Providers\CommandRecorder\CommandRecorderServiceProvider;
use LaravelZero\Framework\Providers\NullLogger\NullLoggerServiceProvider;
use NunoMaduro\LaravelConsoleSummary\LaravelConsoleSummaryServiceProvider;
use NunoMaduro\LaravelConsoleTask\LaravelConsoleTaskServiceProvider;
use NunoMaduro\LaravelDesktopNotifier\LaravelDesktopNotifierServiceProvider;

use function collect;

/**
 * @internal
 */
final class RegisterProviders implements BootstrapperContract
{
    /**
     * Core providers.
     *
     * @var string[]
     */
    protected $providers = [
        NullLoggerServiceProvider::class,
        CollisionServiceProvider::class,
        Providers\Cache\CacheServiceProvider::class,
        Providers\Filesystem\FilesystemServiceProvider::class,
        Providers\Composer\ComposerServiceProvider::class,
        LaravelDesktopNotifierServiceProvider::class,
        LaravelConsoleTaskServiceProvider::class,
        LaravelConsoleSummaryServiceProvider::class,
        CommandRecorderServiceProvider::class,
    ];

    /**
     * Optional components.
     *
     * @var string[]
     */
    protected $components = [
        Components\Log\Provider::class,
        Components\Queue\Provider::class,
        Components\Updater\Provider::class,
        Components\Database\Provider::class,
        Components\ConsoleDusk\Provider::class,
        Components\Menu\Provider::class,
        Components\Redis\Provider::class,
        Components\View\Provider::class,
    ];

    /**
     * {@inheritdoc}
     */
    public function bootstrap(Application $app): void
    {
        /*
         * First, we register Laravel Foundation providers.
         */
        $app->make(BaseRegisterProviders::class)
            ->bootstrap($app);

        /*
         * Register the console schedule implementation.
         */
        $this->registerConsoleSchedule($app);

        /*
         * Then we register Laravel Zero available providers.
         */
        collect($this->providers)
            ->merge(
                collect($this->components)->filter(
                    function ($component) use ($app) {
                        return (new $component($app))->isAvailable();
                    }
                )
            )
            ->each(
                function ($serviceProviderClass) use ($app) {
                    $app->register($serviceProviderClass);
                }
            );
    }

    /**
     * Register the console schedule implementation.
     */
    public function registerConsoleSchedule(Application $app): void
    {
        $app->singleton(Schedule::class, function ($app) {
            return $app->make(ConsoleKernel::class)->resolveConsoleSchedule();
        });
    }
}
