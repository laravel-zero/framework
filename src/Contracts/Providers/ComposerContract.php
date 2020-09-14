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

namespace LaravelZero\Framework\Contracts\Providers;

/**
 * @internal
 */
interface ComposerContract
{
    /**
     * Runs a composer require with the provided package.
     */
    public function require(string $package, bool $dev = false): bool;

    /**
     * Runs a composer remove with the provided package.
     */
    public function remove(string $package, bool $dev = false): bool;

    /**
     * Runs a composer create-project.
     *
     * Usage: createProject(
     *     'laravel-zero/laravel-zero',
     *     'my-project-name',
     *     [--prefer-dist]
     * )
     */
    public function createProject(string $skeleton, string $projectName, array $options): bool;
}
