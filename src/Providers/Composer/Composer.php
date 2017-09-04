<?php

/**
 * This file is part of Zero Framework.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace LaravelZero\Framework\Providers\Composer;

use LaravelZero\Framework\Contracts\Providers\Composer as ComposerContract;

/**
 * This is the Zero Framework composer class.
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
        exec('cd '.BASE_PATH." && composer require $package");

        return $this;
    }
}
