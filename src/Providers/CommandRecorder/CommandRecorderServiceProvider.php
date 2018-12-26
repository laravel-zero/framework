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

namespace LaravelZero\Framework\Providers\CommandRecorder;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * @internal
 */
final class CommandRecorderServiceProvider extends BaseServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function register(): void
    {
        $this->app->singleton(CommandRecorderRepository::class, function () {
            return new CommandRecorderRepository;
        });
    }
}
