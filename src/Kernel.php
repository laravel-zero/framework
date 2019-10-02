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

namespace LaravelZero\Framework;

use function collect;
use function define;
use function defined;
use function get_class;
use Illuminate\Console\Application as Artisan;
use Illuminate\Foundation\Console\Kernel as BaseKernel;
use function in_array;
use LaravelZero\Framework\Providers\CommandRecorder\CommandRecorderRepository;
use ReflectionClass;
use Symfony\Component\Console\Output\OutputInterface;

class Kernel extends BaseKernel
{
    /**
     * The application's development commands.
     *
     * @var string[]
     */
    protected $developmentCommands = [
        Commands\BuildCommand::class,
        Commands\RenameCommand::class,
        Commands\MakeCommand::class,
        Commands\InstallCommand::class,
    ];

    /**
     * The application's bootstrap classes.
     *
     * @var string[]
     */
    protected $bootstrappers = [
        \LaravelZero\Framework\Bootstrap\CoreBindings::class,
        \LaravelZero\Framework\Bootstrap\LoadEnvironmentVariables::class,
        \LaravelZero\Framework\Bootstrap\LoadConfiguration::class,
        \Illuminate\Foundation\Bootstrap\HandleExceptions::class,
        \LaravelZero\Framework\Bootstrap\RegisterFacades::class,
        \LaravelZero\Framework\Bootstrap\RegisterProviders::class,
        \Illuminate\Foundation\Bootstrap\BootProviders::class,
    ];

    /**
     * Kernel constructor.
     */
    public function __construct(
        \Illuminate\Contracts\Foundation\Application $app,
        \Illuminate\Contracts\Events\Dispatcher $events
    ) {
        if (! defined('ARTISAN_BINARY')) {
            define('ARTISAN_BINARY', basename($_SERVER['SCRIPT_FILENAME']));
        }

        parent::__construct($app, $events);
    }

    /**
     * {@inheritdoc}
     */
    public function handle($input, $output = null)
    {
        $this->app->instance(OutputInterface::class, $output);

        return parent::handle($input, $output);
    }

    /**
     * {@inheritdoc}
     */
    public function bootstrap(): void
    {
        parent::bootstrap();

        if ($commandClass = $this->app['config']->get('commands.default')) {
            $this->getArtisan()
                ->setDefaultCommand(
                    $this->app[$commandClass]->getName()
                );
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function commands(): void
    {
        $config = $this->app['config'];

        /*
         * Loads commands paths.
         */
        $this->load($config->get('commands.paths', $this->app->path('Commands')));

        /**
         * Loads configurated commands.
         */
        $commands = collect($config->get('commands.add', []))->merge($config->get('commands.hidden', []));

        if ($command = $config->get('commands.default')) {
            $commands->push($command);
        }

        /*
         * Loads development commands.
         */
        if ($this->app->environment() !== 'production') {
            $commands = $commands->merge($this->developmentCommands);
        }

        $commands = $commands->diff($toRemoveCommands = $config->get('commands.remove', []));

        Artisan::starting(
            function ($artisan) use ($toRemoveCommands) {
                $reflectionClass = new ReflectionClass(Artisan::class);
                $commands = collect($artisan->all())
                    ->filter(
                        function ($command) use ($toRemoveCommands) {
                            return ! in_array(get_class($command), $toRemoveCommands, true);
                        }
                    )
                    ->toArray();

                $property = $reflectionClass->getParentClass()
                    ->getProperty('commands');

                $property->setAccessible(true);
                $property->setValue($artisan, $commands);
                $property->setAccessible(false);
            }
        );

        /*
         * Registers a bootstrap callback on the artisan console application
         * in order to call the schedule method on each Laravel Zero
         * command class.
         */
        Artisan::starting(
            function ($artisan) use ($commands) {
                $artisan->resolveCommands($commands->toArray());
            }
        );

        Artisan::starting(
            function ($artisan) use ($config) {
                collect($artisan->all())->each(
                    function ($command) use ($config, $artisan) {
                        if (in_array(get_class($command), $config->get('commands.hidden', []), true)) {
                            $command->setHidden(true);
                        }

                        $command->setApplication($artisan);

                        if ($command instanceof Commands\Command) {
                            $this->app->call([$command, 'schedule']);
                        }
                    }
                );
            }
        );
    }

    /**
     * Gets the application name.
     */
    public function getName(): string
    {
        return $this->getArtisan()
            ->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function call($command, array $parameters = [], $outputBuffer = null)
    {
        resolve(CommandRecorderRepository::class)->create($command, $parameters);

        return parent::call($command, $parameters, $outputBuffer);
    }
}
