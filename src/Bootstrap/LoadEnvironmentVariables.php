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

use function class_exists;
use Illuminate\Contracts\Foundation\Application;
use LaravelZero\Framework\Contracts\BoostrapperContract;
use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables as BaseLoadEnvironmentVariables;

final class LoadEnvironmentVariables extends BaseLoadEnvironmentVariables implements BoostrapperContract
{
    /**
     * {@inheritdoc}
     */
    public function bootstrap(Application $app): void
    {
        if (class_exists(\Dotenv\Dotenv::class)) {
            parent::bootstrap($app);
        }
    }
}
