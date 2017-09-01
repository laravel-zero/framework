<?php

/**
 * This file is part of Zero Framework.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace NunoMaduro\ZeroFramework\Providers\Composer;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use NunoMaduro\ZeroFramework\Contracts\Providers\Composer as ComposerContract;

/**
 * The is the Zero Framework composer service provider class.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register composer service.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('composer', function ($app) {
            return new Composer;
        });

        $this->app->alias('composer', [ComposerContract::class]);
    }
}
