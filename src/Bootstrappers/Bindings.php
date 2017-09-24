<?php

namespace LaravelZero\Framework\Bootstrappers;

use Illuminate\Config\Repository;
use Illuminate\Container\Container;
use LaravelZero\Framework\Contracts\Application as ApplicationContract;

/**
 * This is the Zero Framework Bootstrapper Bindings class.
 *
 * Register the basic bindings into the container.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
class Bindings extends Bootstrapper
{
    /**
     * {@inheritdoc}
     */
    public function bootstrap(): void
    {
        Container::setInstance($this->container);

        $this->container->instance('app', $this->container);

        $this->container->instance(Container::class, $this->container);

        $this->container->instance(ApplicationContract::class, $this->application);

        $this->container->instance(
            'config',
            new Repository(
                require BASE_PATH.'/'.'config/config.php'
            )
        );
    }
}
