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

/**
 * This is the Laravel Zero Framework Bootstrapper Load Environment Variables implementation.
 */
class LoadEnvironmentVariables
{
    /**
     * If component installed, bootstrap Environment Variables if component installed.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     */
    public function bootstrap(Application $app): void
    {
        if (class_exists(\Dotenv\Dotenv::class)) {
            $app->make(\Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables::class)->bootstrap(
                $app
            );
        }
    }
}
