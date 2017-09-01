<?php

/**
 * This file is part of Zero Framework.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace NunoMaduro\ZeroFramework\Providers;

use NunoMaduro\ZeroFramework\Contracts\Composer as ComposerContract;

/**
 * The is the Zero Framework composer class.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
class Composer implements ComposerContract
{
    /**
     * {@inheritdoc}
     */
    public function require(string $package): ComposerContract
    {
        $this->info("Pulling $package...");

        exec('cd '.BASE_PATH." && composer require $package");

        return $this;
    }
}
