<?php

namespace LaravelZero\Framework;

use ArrayAccess;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Traits\CapsuleManagerTrait;
use Symfony\Component\Console\Input\InputInterface;
use Illuminate\Console\Application as BaseApplication;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Contracts\Container\Container as ContainerContract;
use LaravelZero\Framework\Contracts\Application as ApplicationContract;

/**
 * This is the Zero Framework application class.
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
        parent::__construct($this->container, $this->dispatcher, '');

        $this->setCatchExceptions(true);
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
}
