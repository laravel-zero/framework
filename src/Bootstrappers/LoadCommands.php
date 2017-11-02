<?php

namespace LaravelZero\Framework\Bootstrappers;

use LaravelZero\Framework\Commands;
use Illuminate\Contracts\Config\Repository;

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
     * The application's development commands.
     *
     * @var string[]
     */
    protected $developmentCommands = [
        Commands\App\Builder::class,
        Commands\App\Renamer::class,
        Commands\Component\Illuminate\Database\Installer::class,
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
                $this->getDetectedCommands($config)
            )
        );

        if (! $config->get('app.production')) {
            $commands = $commands->merge($this->developmentCommands);
        }

        $commands->push($config->get('app.default-command'))
            ->unique()
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

    /**
     * Returns the detected commands.
     *
     * @param \Illuminate\Contracts\Config\Repository $config
     *
     * @return array
     */
    protected function getDetectedCommands(Repository $config): array
    {
        $commands = [];

        $namespaces = $config->get('commands-namespaces') ?: ["App\Commands"];

        foreach ($namespaces as $namespace) {
            $parts = explode('\\', $namespace);
            $path = base_path(implode(DIRECTORY_SEPARATOR, $parts));

            foreach (glob("$path/*.php") as $commandFile) {
                $commandClass = pathinfo($commandFile)['filename'];
                $commands[] = $namespace.'\\'.$commandClass;
            }
        }

        return $commands;
    }
}
