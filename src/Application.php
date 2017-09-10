<?php

namespace LaravelZero\Framework;

use ArrayAccess;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Illuminate\Support\Traits\CapsuleManagerTrait;
use Symfony\Component\Console\Input\InputInterface;
use LaravelZero\Framework\Contracts\Application as ApplicationContract;
use Illuminate\Console\Application as BaseApplication;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Contracts\Container\Container as ContainerContract;

/**
 * This is the Zero Framework application class.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
class Application extends BaseApplication implements ApplicationContract, ArrayAccess
{
    use CapsuleManagerTrait, ContainerProxyTrait;

    /**
     * The application event dispatcher.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $dispatcher;

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
        static::$bootstrappers = ($bootstrappersFactory ?: new Bootstrappers\Factory)->make();
        $this->setCatchExceptions(true);

        parent::__construct($this->container, $this->dispatcher, '');
    }

    /**
     * Gets the name of the command based on input.
     *
     * Proxies to the Laravel default command if there is no application
     * default command.
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input The input interface
     *
     * @return string With the command name that should be executed.
     */
    protected function getCommandName(InputInterface $input): string
    {
        if (($name = parent::getCommandName($input)) || (! $defaultCommand = $this->container->make('config')
                ->get('app.default-command'))) {
            return $name;
        }

        return $this->container->make($defaultCommand)
            ->getName();
    }
}
