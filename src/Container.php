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
     * {@inheritdoc}
     */
    public function version()
    {
        return config('app.name');
    }

    /**
     * {@inheritdoc}
     */
    public function basePath()
    {
        return BASE_PATH;
    }

    /**
     * {@inheritdoc}
     */
    public function databasePath()
    {
        return config('database.path') ?: (BASE_PATH.'/database');
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
}
