<?php

namespace LaravelZero\Framework;

use Illuminate\Container\Container as BaseContainer;
use LaravelZero\Framework\Exceptions\NotImplementedException;
use Illuminate\Contracts\Foundation\Application as LaravelApplication;

/**
 * This is the Laravel Zero Framework container class.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
class Container extends BaseContainer implements LaravelApplication
{
    /**
     * A custom callback used to configure Monolog.
     *
     * @var callable|null
     */
    protected $monologConfigurator;

    /**
     * {@inheritdoc}
     */
    public function version()
    {
        return config('app.version');
    }

    /**
     * Get the base path of the Laravel installation.
     *
     * @param  string $path
     *
     * @return string
     */
    public function basePath($path = '')
    {
        return BASE_PATH.($path ? DIRECTORY_SEPARATOR.$path : $path);
    }

    /**
     * Get the path to the application configuration files.
     *
     * @param  string $path
     *
     * @return string
     */
    public function configPath($path = '')
    {
        return BASE_PATH.DIRECTORY_SEPARATOR.'config'.($path ? DIRECTORY_SEPARATOR.$path : $path);
    }

    /**
     * Get the path to the database directory.
     *
     * @param  string $path
     *
     * @return string
     */
    public function databasePath($path = '')
    {
        return (BASE_PATH.DIRECTORY_SEPARATOR.'database').($path ? DIRECTORY_SEPARATOR.$path : $path);
    }

    /**
     * Get the path to the language files.
     *
     * @return string
     */
    public function langPath()
    {
        return $this->resourcePath('lang');
    }

    /**
     * Get the path to the resources directory.
     *
     * @param  string $path
     *
     * @return string
     */
    public function resourcePath($path = '')
    {
        return BASE_PATH.DIRECTORY_SEPARATOR.'resources'.($path ? DIRECTORY_SEPARATOR.$path : $path);
    }

    /**
     * Get the path to the storage directory.
     *
     * @return string
     */
    public function storagePath()
    {
        return BASE_PATH.DIRECTORY_SEPARATOR.'storage';
    }

    /**
     * {@inheritdoc}
     */
    public function environment()
    {
        return config('app.production') ? 'production' : 'development';
    }

    /**
     * {@inheritdoc}
     */
    public function runningInConsole()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getNamespace()
    {
        return 'App';
    }

    /**
     * {@inheritdoc}
     */
    public function isDownForMaintenance()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function registerConfiguredProviders()
    {
        throw new NotImplementedException;
    }

    /**
     * {@inheritdoc}
     */
    public function register($provider, $options = [], $force = false)
    {
        throw new NotImplementedException;
    }

    /**
     * {@inheritdoc}
     */
    public function registerDeferredProvider($provider, $service = null)
    {
        throw new NotImplementedException;
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        throw new NotImplementedException;
    }

    /**
     * {@inheritdoc}
     */
    public function booting($callback)
    {
        throw new NotImplementedException;
    }

    /**
     * {@inheritdoc}
     */
    public function booted($callback)
    {
        throw new NotImplementedException;
    }

    /**
     * {@inheritdoc}
     */
    public function getCachedServicesPath()
    {
        throw new NotImplementedException;
    }

    /**
     * {@inheritdoc}
     */
    public function getCachedPackagesPath()
    {
        throw new NotImplementedException;
    }

    /**
     * Define a callback to be used to configure Monolog.
     *
     * @param  callable $callback
     *
     * @return $this
     */
    public function configureMonologUsing(callable $callback)
    {
        $this->monologConfigurator = $callback;

        return $this;
    }

    /**
     * Determine if the application has a custom Monolog configurator.
     *
     * @return bool
     */
    public function hasMonologConfigurator()
    {
        return ! is_null($this->monologConfigurator);
    }

    /**
     * Get the custom Monolog configurator for the application.
     *
     * @return callable
     */
    public function getMonologConfigurator()
    {
        return $this->monologConfigurator;
    }
}
