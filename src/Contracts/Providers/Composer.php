<?php

namespace LaravelZero\Framework\Contracts\Providers;

/**
 * This is the Zero Framework composer contract.
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
}
