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
 * This is the Laravel Zero Framework Bootstrap Constants implementation.
 */
class Constants
{
    /**
     * Bootstrap core constants.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return void
     */
    public function bootstrap(Application $app): void
    {
        if (! defined('ARTISAN_BINARY')) {
            define('ARTISAN_BINARY', $app->basePath(basename($_SERVER['SCRIPT_FILENAME'])));
        }
    }
}
