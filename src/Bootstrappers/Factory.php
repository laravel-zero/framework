<?php

namespace LaravelZero\Framework\Bootstrappers;

use Illuminate\Contracts\Console\Application as ApplicationContract;

/**
 * This is the Zero Framework Bootstrappers factory class.
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
        Constants::class,
        Bindings::class,
        ServiceProviders::class,
        Configuration::class,
    ];

    /**
     * Returns all core bootstrappers.
     *
     * @return []string
     */
    public function make(): array
    {
        return array_map(function($bootstrapper) {
            return function (ApplicationContract $application) use ($bootstrapper) {
                return (new $bootstrapper($application))->bootstrap();
            };
        }, $this->bootstrappers);
    }
}
