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

use LaravelZero\Framework\Application;
use LaravelZero\Framework\Contracts\BoostrapperContract;
use LaravelZero\Framework\Providers\GitVersion\GitVersionServiceProvider;

/**
 * @internal
 */
final class CoreBindings implements BoostrapperContract
{
    /**
     * {@inheritdoc}
     */
    public function bootstrap(Application $app): void
    {
        (new GitVersionServiceProvider($app))->register();
    }
}
