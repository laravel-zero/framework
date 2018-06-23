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

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\PackageManifest;
use Illuminate\Events\EventServiceProvider;
use LaravelZero\Framework\Exceptions\ConsoleException;
use Illuminate\Foundation\Application as BaseApplication;
use Symfony\Component\Console\Exception\CommandNotFoundException;

class Application extends BaseApplication
{
    /**
     * Get the builds path.
     *
     * @param  string $path Optionally, a path to append to the base path
     *
     * @return string
     */
    public function buildsPath(string $path = ''): string
    {
        return $this->basePath('builds'.($path ? DIRECTORY_SEPARATOR.$path : $path));
    }

    /**
     * {@inheritdoc}
     */
    protected function registerBaseBindings(): void
    {
        parent::registerBaseBindings();

        /*
         * Ignores auto-discovery.
         */
        $this->make(PackageManifest::class)->manifest = [];
    }

    /**
     * {@inheritdoc}
     */
    protected function registerBaseServiceProviders(): void
    {
        $this->register(new EventServiceProvider($this));
    }

    /**
     * {@inheritdoc}
     */
    public function version()
    {
        return $this['config']->get('app.version');
    }

    /**
     * {@inheritdoc}
     */
    public function runningInConsole(): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isDownForMaintenance(): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function configurationIsCached(): bool
    {
        return false;
    }

    /**
     * Register all of the configured providers.
     *
     * @return void
     */
    public function registerConfiguredProviders(): void
    {
        $providers = Collection::make($this['config']['app.providers'])
            ->partition(
                function ($provider) {
                    return Str::startsWith($provider, 'Illuminate\\');
                }
            );

        $providers->splice(
            1,
            0,
            [
                $this->make(PackageManifest::class)
                    ->providers(),
            ]
        );

        (new ProviderRepository($this, new Filesystem, $this->getCachedServicesPath()))->load(
            $providers->collapse()
                ->toArray()
        );
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
     * @throws \LaravelZero\Framework\Contracts\Exceptions\ConsoleExceptionContract
     */
    public function abort($code, $message = '', array $headers = []): void
    {
        if ($code === 404) {
            throw new CommandNotFoundException($message);
        }

        throw new ConsoleException($code, $message, $headers);
    }
}
