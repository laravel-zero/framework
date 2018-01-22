<?php

/**
 * This file is part of Laravel Zero.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace LaravelZero\Framework;

use Illuminate\Foundation\Console\Kernel as BaseKernel;

/**
 * This is the Laravel Zero Kernel implementation.
 */
class Kernel extends BaseKernel
{
    /**
     * The application's development commands.
     *
     * @var string[]
     */
    protected $developmentCommands = [
        Commands\App\Builder::class,
        Commands\App\Renamer::class,
        Commands\App\CommandMaker::class,
        Commands\Component\Illuminate\Log\Installer::class,
        Commands\Component\Vlucas\Phpdotenv\Installer::class,
        Commands\Component\Illuminate\Database\Installer::class,
    ];

    /**
     * The bootstrap classes for the application.
     *
     * @var array
     */
    protected $bootstrappers = [
        \LaravelZero\Framework\Bootstrap\LoadEnvironmentVariables::class,
        \Illuminate\Foundation\Bootstrap\LoadConfiguration::class,
        \Illuminate\Foundation\Bootstrap\HandleExceptions::class,
        \Illuminate\Foundation\Bootstrap\RegisterFacades::class,
        \LaravelZero\Framework\Bootstrap\RegisterProviders::class,
        \Illuminate\Foundation\Bootstrap\BootProviders::class,
    ];

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load($this->app->path('Commands'));

        $config = $this->app->make('config');

        if ($name = $config->get('app.name')) {
            $this->getArtisan()->setName($name);
        }

        $commands = collect($config->get('app.commands') ?: []);

        if ($this->app->environment() !== 'production') {
            $commands = $commands->merge($this->developmentCommands);
        }

        if ($config->get('app.with-scheduler')) {
            $commands = $commands->merge(
                [
                    \Illuminate\Console\Scheduling\ScheduleRunCommand::class,
                    \Illuminate\Console\Scheduling\ScheduleFinishCommand::class,
                ]
            );
        }

        $commands->push($config->get('app.default-command'))->unique()->each(
            function ($command) {
                if ($command) {
                    $commandInstance = $this->app->make($command);

                    if ($commandInstance instanceof Commands\Command) {
                        $this->app->call([$commandInstance, 'schedule']);
                    }

                    $this->registerCommand($commandInstance);
                }
            }
        );
    }
}
