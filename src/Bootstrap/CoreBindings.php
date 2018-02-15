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
use LaravelZero\Framework\Providers\GitVersion\GitVersionServiceProvider;

/**
 * This is the Laravel Zero Framework Bootstrap Core Bindings implementation.
 */
class CoreBindings
{
    /**
     * Registers service providers that need to be registered
     * on the early stage of the framework.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     */
    public function bootstrap(Application $app): void
    {
        (new GitVersionServiceProvider($app))->register();
    }
}
