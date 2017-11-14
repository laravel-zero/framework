<?php

namespace LaravelZero\Framework\Bootstrappers;

use ReflectionClass;
use Illuminate\Support\Str;
use LaravelZero\Framework\Commands;
use Symfony\Component\Finder\Finder;
use LaravelZero\Framework\Commands\Command;
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
        Commands\App\CommandMaker::class,
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

        $paths = collect($config->get('app.commands-paths', ["Commands"]))
            ->filter(
                function ($path) {
                    return file_exists(base_path('app'.DIRECTORY_SEPARATOR.$path));
                }
            )
            ->map(
                function ($path) {
                    return base_path('app'.DIRECTORY_SEPARATOR.$path);
                }
            )->toArray();

        $namespace = app()->getNamespace()."\\";
        if (! empty($paths)) {
            foreach ((new Finder)->in($paths)
                         ->files() as $command) {
                $command = $namespace.str_replace(
                        ['/', '.php'],
                        ['\\', ''],
                        Str::after($command->getPathname(), base_path('app').DIRECTORY_SEPARATOR)
                    );
                if (is_subclass_of($command, Command::class) && ! (new ReflectionClass($command))->isAbstract()) {
                    $commands[] = $command;
                }
            }
        }

        return $commands;
    }
}
