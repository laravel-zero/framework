<?php

namespace LaravelZero\Framework\Contracts\Providers;

/**
 * This is the Laravel Zero Framework composer contract.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
interface Composer
{
    /**
     * Pulls the provided package.
     *
     * @param  string $package
     *
     * @return $this
     */
    public function require(string $package): Composer;

    /**
     * Creates an project.
     *
     * Usage: createProject(
     *     'laravel-zero/laravel-zero',
     *     'my-project-name',
     *     [--prefer-dist]
     * )
     *
     * @param  string $package
     * @param  string $name
     * @param  array $options
     *
     * @return $this
     */
    public function createProject(string $package, string $name, array $options): Composer;
}
