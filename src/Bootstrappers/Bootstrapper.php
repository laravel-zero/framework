<?php

namespace LaravelZero\Framework\Bootstrappers;

use Illuminate\Contracts\Container\Container;
use LaravelZero\Framework\Contracts\Application;

/**
 * This is the Laravel Zero Framework abstract bootstrapper class.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
abstract class Bootstrapper
{
    /**
     * Holds an instance of application.
     *
     * @var \Illuminate\Contracts\Console\Application
     */
    protected $application;

    /**
     * Holds an instance of the container.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * Creates a new instance of the bootstrapper.
     *
     * @param \LaravelZero\Framework\Contracts\Application $application
     * @param \Illuminate\Contracts\Container\Container $container
     */
    public function __construct(Application $application, Container $container = null)
    {
        $this->application = $application;
        $this->container = $container ?: $application->getContainer();
    }

    /**
     * Bootstraps the bootstrapper.
     *
     * @return void
     */
    abstract public function bootstrap(): void;
}
