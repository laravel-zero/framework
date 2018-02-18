<?php

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
 * This is the Laravel Zero Framework Cache Service Provider implementation.
 */
class CacheServiceProvider extends BaseServiceProvider
{
    /**
     * Register the Cache Service Provider.
     *
     * @return void
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
     *
     * @return array
     */
    protected function getDefaultConfig(): array
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
