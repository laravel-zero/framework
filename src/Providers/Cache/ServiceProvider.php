<?php

namespace LaravelZero\Framework\Providers\Cache;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Cache\Cache as BaseServiceProvider;

/**
 * This is the Laravel Zero Framework Filesystem service provider class.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register Scheduler service.
     *
     * @return void
     */
    public function register(): void
    {
        parent::register();

        $config = $this->app->make('config');

        if ($config->get('cache') === null) {
            $config->set('cache', $this->getCacheConfig());
        }
    }

    /**
     * Returns the default application cache config.
     *
     * In order to keep it simple we use the `array` driver. Feel free
     * to use another driver, be sure to check the cache component
     * documentation.
     *
     * @return array
     */
    protected function getCacheConfig(): array
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
