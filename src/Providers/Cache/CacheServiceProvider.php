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

namespace LaravelZero\Framework\Providers\Cache;

use Illuminate\Cache\CacheServiceProvider as BaseServiceProvider;

/**
 * @internal
 */
final class CacheServiceProvider extends BaseServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function register(): void
    {
        parent::register();

        if ($this->app['config']->get('cache') === null) {
            $this->app['config']->set('cache', $this->getDefaultConfig());
        }
    }

    /**
     * Returns the default application cache config.
     *
     * We keep it simple using the `array` driver.
     */
    private function getDefaultConfig(): array
    {
        return [
            'default' => 'array',
            'stores' => [
                'array' => [
                    'driver' => 'array',
                ],
            ],
        ];
    }
}
