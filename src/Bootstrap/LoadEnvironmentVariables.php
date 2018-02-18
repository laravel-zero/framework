<?php

/**
 * This file is part of Laravel Zero.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace LaravelZero\Framework\Bootstrap;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables as BaseLoadEnvironmentVariables;

/**
 * This is the Laravel Zero Framework Bootstrap Load Environment Variables implementation.
 */
class LoadEnvironmentVariables extends BaseLoadEnvironmentVariables
{
    /**
     * If component is installed, bootstrap Environment Variables.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     */
    public function bootstrap(Application $app): void
    {
        if (class_exists(\Dotenv\Dotenv::class)) {
            parent::bootstrap($app);
        }
    }
}
