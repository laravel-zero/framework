<?php

/**
 * This file is part of Laravel Zero.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace LaravelZero\Framework\Contracts\Providers;

/**
 * This is the Laravel Zero Framework Composer Contract.
 */
interface Composer
{
    /**
     * Pulls the provided package.
     *
     * @param  string $package
     *
     * @return bool
     */
    public function require(string $package): bool;

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
     * @return bool
     */
    public function createProject(string $package, string $name, array $options): bool;
}
