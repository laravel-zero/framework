<?php

namespace LaravelZero\Framework;

use Illuminate\Events\Dispatcher;
use Symfony\Component\Console\Command\Command;
use Illuminate\Support\Traits\CapsuleManagerTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Illuminate\Console\Application as BaseApplication;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Contracts\Container\Container as ContainerContract;
use LaravelZero\Framework\Contracts\Application as ApplicationContract;
use LaravelZero\Framework\Contracts\Providers\ErrorHandler as ErrorHandlerContract;

/**
 * This is the Laravel Zero Framework application class.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
class Application extends BaseApplication implements ApplicationContract
{
    use CapsuleManagerTrait;

    /**
     * The application event dispatcher.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $dispatcher;

    /**
     * The current running command.
     *
     * @var \Symfony\Component\Console\Command\Command
     */
    protected $runningCommand;

    /**
     * Holds an instance of the bootstrapper factory.
     *
     * @var \LaravelZero\Framework\Bootstrappers\Factory
     */
    protected $bootstrappersFactory;

    /**
     * Creates a new instance of the application.
     *
     * @param \Illuminate\Contracts\Container\Container|null $container
     * @param \Illuminate\Contracts\Events\Dispatcher|null $dispatcher
     * @param \LaravelZero\Framework\Bootstrappers\Factory|null $bootstrappersFactory
     */
    public function __construct(
        ContainerContract $container = null,
        DispatcherContract $dispatcher = null,
        Bootstrappers\Factory $bootstrappersFactory = null
    ) {
        $this->setupContainer($container ?: new Container);
        $this->dispatcher = $dispatcher ?: new Dispatcher($this->container);
        $this->bootstrappersFactory = $bootstrappersFactory ?: new Bootstrappers\Factory;
        parent::__construct($this->container, $this->dispatcher, '');

        $this->setCatchExceptions(true);
    }

    /**
     * {@inheritdoc}
     */
    protected function bootstrap()
    {
        foreach ($this->bootstrappersFactory->make() as $bootstrapper) {
            $bootstrapper($this);
        }

        parent::bootstrap();
    }

    /**
     * Gets the name of the command based on input.
     *
     * Proxies to the Laravel default command if there is no application
     * default command.
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input The input interface
     *
     * @return string|null
     */
    protected function getCommandName(InputInterface $input)
    {
        if (($name = parent::getCommandName($input)) || ($defaultCommand = $this->container->make('config')
                ->get('app.default-command')) === null) {
            return $name;
        }

        return $this->container->make($defaultCommand)
            ->getName();
    }

    /**
     * {@inheritdoc}
     */
    protected function doRunCommand(Command $command, InputInterface $input, OutputInterface $output)
    {
        $this->container->make(ErrorHandlerContract::class)->setOutput($output);

        return parent::doRunCommand($this->runningCommand = $command, $input, $output);
    }

    /**
     * {@inheritdoc}
     */
    public function getRunningCommand(): Command
    {
        return $this->runningCommand;
    }
}
