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

namespace LaravelZero\Framework\Components\ScheduleList;

use function class_exists;
use LaravelZero\Framework\Components\AbstractComponentProvider;

/**
 * @internal
 */
final class Provider extends AbstractComponentProvider
{
    /**
     * {@inheritdoc}
     */
    public function isAvailable(): bool
    {
        return class_exists(\Hmazter\LaravelScheduleList\ScheduleListServiceProvider::class) && is_array(
                $this->app['config']->get('schedule-list', false)
            );
    }

    /**
     * {@inheritdoc}
     */
    public function register(): void
    {
        $this->app->register(\Hmazter\LaravelScheduleList\ScheduleListServiceProvider::class);
    }
}
