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

namespace LaravelZero\Framework\Providers\NullLogger;

use Psr\Log\NullLogger;
use Psr\Log\LoggerInterface;
use Illuminate\Support\ServiceProvider;

/**
 * @internal
 */
final class NullLoggerServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function register(): void
    {
        $this->app->singleton(LoggerInterface::class, NullLogger::class);
    }
}
