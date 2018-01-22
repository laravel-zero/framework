<?php

/**
 * This file is part of Laravel Zero.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace LaravelZero\Framework;

use Illuminate\Events\EventServiceProvider;
use LaravelZero\Framework\Exceptions\ConsoleException;
use Illuminate\Foundation\Application as BaseApplication;
use Symfony\Component\Console\Exception\CommandNotFoundException;

/**
 * This is the Laravel Zero Framework application implementation.
 */
class Application extends BaseApplication
{
    /**
     * Get the builds path of the application.
     *
     * @return string
     */
    public function buildsPath(): string
    {
        return $this->basePath.DIRECTORY_SEPARATOR.'builds';
    }

    /**
     * {@inheritdoc}
     */
    protected function registerBaseServiceProviders()
    {
        $this->register(new EventServiceProvider($this));
    }

    /**
     * {@inheritdoc}
     */
    public function version()
    {
        return config('app.version');
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
    public function configurationIsCached()
    {
        return false;
    }

    /**
     * Throw an Console Exception with the given data unless the given condition is true.
     *
     * @param  int $code
     * @param  string $message
     * @param  array $headers
     * @return void
     *
     * @throws \Symfony\Component\Console\Exception\CommandNotFoundException
     * @throws \LaravelZero\Framework\Contracts\Exceptions\ConsoleException
     */
    public function abort($code, $message = '', array $headers = [])
    {
        if ($code == 404) {
            throw new CommandNotFoundException($message);
        }

        throw new ConsoleException($code, $message, $headers);
    }
}
