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

use Illuminate\Console\Application as Artisan;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Bootstrap\BootProviders;
use Illuminate\Foundation\Bootstrap\HandleExceptions;
use Illuminate\Foundation\Console\ConfigPublishCommand;
use Illuminate\Foundation\Console\Kernel as BaseKernel;
use LaravelZero\Framework\Bootstrap\CoreBindings;
use LaravelZero\Framework\Bootstrap\LoadConfiguration;
use LaravelZero\Framework\Bootstrap\LoadEnvironmentVariables;
use LaravelZero\Framework\Bootstrap\RegisterFacades;
use LaravelZero\Framework\Bootstrap\RegisterProviders;
use LaravelZero\Framework\Providers\CommandRecorder\CommandRecorderRepository;
use NunoMaduro\Collision\Adapters\Laravel\Commands\TestCommand;
use ReflectionClass;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Console\Output\OutputInterface;

use function collect;
use function define;
use function defined;
use function get_class;
use function in_array;
use function is_null;

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
        Commands\StubPublishCommand::class,
        Commands\TestMakeCommand::class,

        // ...

        ConfigPublishCommand::class,
    ];

    /**
     * Commands that should be removed in production.
     *
     * @var string[]
     */
    protected $developmentOnlyCommands = [
        TestCommand::class,
    ];

    /**
     * The application's default hidden commands.
     *
     * @var string[]
     */
    protected $hiddenCommands = [
        ConfigPublishCommand::class,
    ];

    /**
     * The commands registered through the "commands" configuration.
     *
     * @var string[]
     */
    protected array $configuredCommands = [];

    /**
     * The commands that should be removed from the application.
     *
     * @var string[]
     */
    protected array $removedCommands = [];

    /**
     * The application's bootstrap classes.
     *
     * @var string[]
     */
    protected $bootstrappers = [
        CoreBindings::class,
        LoadEnvironmentVariables::class,
        LoadConfiguration::class,
        HandleExceptions::class,
        RegisterFacades::class,
        RegisterProviders::class,
        BootProviders::class,
    ];

    /**
     * Kernel constructor.
     */
    public function __construct(
        Application $app,
        Dispatcher $events
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

        if (function_exists('Termwind\renderUsing') && $output) {
            \Termwind\renderUsing($output);
        }

        $this->ensureDefaultCommand($input);

        return parent::handle($input, $output);
    }

    /**
     * With Symfony's default commands, you cannot pass any argument
     * or option because they are ignored. When the first argument
     * does not match any commands, this method ensures given
     * arguments/options are proxied to the default command.
     */
    protected function ensureDefaultCommand($input): void
    {
        $this->bootstrap();

        $application = $this->getArtisan();

        try {
            /**
             * First, we are going to see if the first argument
             * matches any command on our console application.
             */
            if ($commandName = $input->getFirstArgument()) {
                $application->find($commandName);
            }
        } catch (CommandNotFoundException $e) {
            /**
             * If none, the given arguments and options should
             * be proxied to the application default command.
             */
            $application->setDefaultCommand(
                resolve(config('commands.default'))->getName(), true
            );
        }
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
         *
         * @phpstan-ignore-next-line
         */
        $this->load($config->get('commands.paths', $this->app->path('Commands')));

        /**
         * Loads configured commands.
         */
        $commands = collect($config->get('commands.add', []))->merge(
            $config->get('commands.hidden', $this->hiddenCommands),
        );

        if ($command = $config->get('commands.default')) {
            $commands->push($command);
        }

        /*
         * Loads development commands.
         */
        if ($this->app->environment() !== 'production') {
            $commands = $commands->merge($this->developmentCommands);
        }

        $toRemoveCommands = $config->get('commands.remove', []);

        if ($this->app->environment('production')) {
            $toRemoveCommands = array_merge($toRemoveCommands, $this->developmentOnlyCommands);
        }

        $this->configuredCommands = $commands->diff($toRemoveCommands)->values()->all();

        $this->removedCommands = $toRemoveCommands;
    }

    /**
     * {@inheritdoc}
     *
     * Commands loaded through the "load" and "command" methods are resolved
     * by the base kernel after the "starting" callbacks have been run, so
     * the configured commands are registered once the console exists.
     */
    protected function getArtisan()
    {
        if (is_null($this->artisan)) {
            $this->configureArtisan(parent::getArtisan());
        }

        return $this->artisan;
    }

    /**
     * Registers, removes, hides and schedules the application commands.
     */
    protected function configureArtisan(Artisan $artisan): void
    {
        $artisan->resolveCommands($this->configuredCommands);

        /*
         * Commands with the "AsCommand" attribute are lazily loaded through
         * the command map, so removed commands are also dropped from it.
         */
        $commandMap = (new ReflectionClass(Artisan::class))->getProperty('commandMap');

        $commandMap->setValue($artisan, collect($commandMap->getValue($artisan))
            ->reject(fn ($command) => in_array($command, $this->removedCommands, true))
            ->all());

        $artisan->setContainerCommandLoader();

        $commands = collect($artisan->all())
            ->filter(
                fn ($command) => ! in_array(get_class($command), $this->removedCommands, true)
            )
            ->toArray();

        (new ReflectionClass(Artisan::class))
            ->getParentClass()
            ->getProperty('commands')
            ->setValue($artisan, $commands);

        $hiddenCommands = array_merge(
            $this->app['config']->get('commands.hidden', []),
            $this->hiddenCommands,
        );

        foreach ($artisan->all() as $command) {
            if (in_array(get_class($command), $hiddenCommands, true)) {
                $command->setHidden(true);
            }

            $command->setApplication($artisan);

            if ($command instanceof Commands\Command) {
                $this->app->call([$command, 'schedule']);
            }
        }
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
        if (function_exists('Termwind\renderUsing') && $outputBuffer) {
            \Termwind\renderUsing($outputBuffer);
        }

        resolve(CommandRecorderRepository::class)->create($command, $parameters);

        return parent::call($command, $parameters, $outputBuffer);
    }
}
