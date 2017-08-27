<?php

/**
 * This file is part of Zero Framework.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace NunoMaduro\ZeroFramework\Contracts\Commands\Component;

/**
 * The is the Zero Framework component finder contract.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
interface Finder
{
    /**
     * Finds all the available components.
     *
     * @return string[]
     */
    public function find(): array;
}
