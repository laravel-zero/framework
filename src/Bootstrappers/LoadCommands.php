<?php

namespace LaravelZero\Framework\Bootstrappers;

use Illuminate\Console\Scheduling;
use LaravelZero\Framework\Commands;

/**
 * This is the Laravel Zero Framework Bootstrapper Configuration class.
 *
 * Configures the console application.
 *
 * Takes in consideration the app name and the app version. Also
 * adds all the application commands.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
class LoadCommands extends Bootstrapper
{
    /**
     * The application's core commands.
     *
     * @var string[]
     */
    protected $commands = [
        Scheduling\ScheduleRunCommand::class,
        Scheduling\ScheduleFinishCommand::class,
    ];

    /**
     * The application's development commands.
     *
     * @var string[]
     */
    protected $developmentCommands = [
        Commands\App\Builder::class,
        Commands\App\Renamer::class,
        Commands\Component\Installer::class,
    ];

    /**
     * {@inheritdoc}
     */
    public function bootstrap(): void
    {
        $config = $this->container->make('config');

        $commands = collect(
            array_merge(
                $config->get('app.commands') ?: [],
                $this->commands
            )
        );

        if (! $config->get('app.production')) {
            $commands = $commands->merge($this->developmentCommands);
        }

        $commands->push($config->get('app.default-command'))
            ->each(
                function ($command) {
                    if ($command) {
                        $commandInstance = $this->container->make($command);

                        if ($commandInstance instanceof Commands\Command) {
                            $this->container->call([$commandInstance, 'schedule']);
                        }

                        $this->application->add($commandInstance);
                    }
                }
            );
    }
}
