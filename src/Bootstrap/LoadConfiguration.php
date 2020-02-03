<?php

declare(strict_types=1);

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
use LaravelZero\Framework\Application;
use LaravelZero\Framework\Contracts\BoostrapperContract;

/**
 * @internal
 */
final class LoadConfiguration implements BoostrapperContract
{
    /**
     * {@inheritdoc}
     */
    public function bootstrap(Application $app): void
    {
        $app->make(BaseLoadConfiguration::class)
            ->bootstrap($app);

        /*
         * When artisan starts, sets the application name and the application version.
         */
        Artisan::starting(
            function ($artisan) use ($app) {
                $artisan->setName($app['config']->get('app.name', 'Laravel Zero'));
                $artisan->setVersion($app->version());
            }
        );
    }
}
