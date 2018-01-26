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

use Illuminate\Console\Application as Artisan;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Bootstrap\LoadConfiguration as BaseLoadConfiguration;

/**
 * This is the Laravel Zero Framework Bootstrap Load Configuration implementation.
 */
class LoadConfiguration extends BaseLoadConfiguration
{
    /**
     * Bootstrap configurations.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     */
    public function bootstrap(Application $app): void
    {
        parent::bootstrap($app);

        /*
         * When artisan starts, sets the application name
         * and the application version.
         */
        Artisan::starting(
            function ($artisan) use ($app) {
                $artisan->setName($app['config']->get('app.name', 'Laravel Zero'));
                $artisan->setVersion($app->version());
            }
        );
    }
}
