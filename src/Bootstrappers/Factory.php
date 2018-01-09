<?php

namespace LaravelZero\Framework\Bootstrappers;

use LaravelZero\Framework\Contracts\Application as ApplicationContract;

/**
 * This is the Laravel Zero Framework Bootstrappers factory class.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
class Factory
{
    /**
     * The core bootstrappers.
     *
     * @var string[]
     */
    private $bootstrappers = [
        LoadEnvironmentVariables::class,
        Constants::class,
        Bindings::class,
        Configurations::class,
        ServiceProviders::class,
        LoadCommands::class,
        Facades::class,
    ];

    /**
     * Returns all core bootstrappers.
     *
     * @return array
     */
    public function make(): array
    {
        return array_map(
            function ($bootstrapper) {
                return function (ApplicationContract $application) use ($bootstrapper) {
                    return (new $bootstrapper($application))->bootstrap();
                };
            },
            $this->bootstrappers
        );
    }
}
