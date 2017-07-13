<?php

/**
 * This file is part of Zero Framework.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace NunoMaduro\ZeroFramework;

use ArrayAccess;
use BadMethodCallException;
use Illuminate\Config\Repository;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Support\Traits\CapsuleManagerTrait;
use Symfony\Component\Console\Input\InputInterface;
use Illuminate\Console\Application as BaseApplication;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Contracts\Container\Container as ContainerContract;

/**
 * The is the Zero Framework application class.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
class Application extends BaseApplication implements ArrayAccess
{
    use CapsuleManagerTrait, ContainerProxy;

    /**
     * The application event dispatcher.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $dispatcher;

    /**
     * The application core aliases.
     *
     * @var array
     */
    protected $aliases = [
        'app' => [\Illuminate\Contracts\Container\Container::class],
        'events' => [\Illuminate\Events\Dispatcher::class, \Illuminate\Contracts\Events\Dispatcher::class],
        'config' => [\Illuminate\Config\Repository::class, \Illuminate\Contracts\Config\Repository::class],
    ];

    /**
     * Creates a new instance of the application class.
     *
     * @param \Illuminate\Contracts\Container\Container|null $container
     * @param \Illuminate\Contracts\Events\Dispatcher|null $dispatcher
     */
    public function __construct(ContainerContract $container = null, DispatcherContract $dispatcher = null)
    {
        $this->setupContainer($container ?: new Container);
        $this->dispatcher = $dispatcher ?: new Dispatcher($this->container);

        parent::__construct($this->container, $this->dispatcher, '');

        $this->setCatchExceptions(true);

        $this->registerBindings()
            ->registerServiceProviders()
            ->registerContainerAliases()
            ->configure();
    }

    /**
     * Gets the name of the command based on input.
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input The input interface
     *
     * @return string The command name
     */
    protected function getCommandName(InputInterface $input): string
    {
        if ($name = parent::getCommandName($input)) {
            return $name;
        }

        return $this->make($this->config->get('app.default-command'))
            ->getName();
    }

    /**
     * Configures the console application.
     *
     * @return $this
     */
    protected function configure(): Application
    {
        $this->setName($this->config->get('app.name'));
        $this->setVersion($this->config->get('app.version'));

        collect($this->config->get('app.commands'))
            ->push($this->config->get('app.default-command'))
            ->each(
                function ($command) {
                    $this->add($this->make($command));
                }
            );

        return $this;
    }

    /**
     * Register the basic bindings into the container.
     *
     * @return $this
     */
    protected function registerBindings(): Application
    {
        Container::setInstance($this->container);

        $this->instance('app', $this);

        $this->instance(Container::class, $this);

        $this->instance(
            'config',
            new Repository(
                require BASE_PATH.'/'.'config/config.php'
            )
        );

        return $this;
    }

    /**
     * Register the services into the container.
     *
     * @return $this
     */
    protected function registerServiceProviders(): Application
    {
        collect($this->config->get('app.providers'))->each(
            function ($serviceProvider) {
                $instance = (new $serviceProvider($this))->register();

                if (method_exists($instance, 'boot')) {
                    $instance->boot();
                }
            }
        );

        return $this;
    }

    /**
     * Register the class aliases in the container.
     *
     * @return $this
     */
    protected function registerContainerAliases(): Application
    {
        foreach ($this->aliases as $key => $aliases) {
            foreach ($aliases as $alias) {
                $this->alias($key, $alias);
            }
        }

        return $this;
    }
}
