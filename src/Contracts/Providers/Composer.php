<?php

/**
 * This file is part of Zero Framework.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace NunoMaduro\ZeroFramework\Contracts\Providers;

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
