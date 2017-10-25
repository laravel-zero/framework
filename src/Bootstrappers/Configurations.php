<?php

namespace LaravelZero\Framework\Bootstrappers;

use Illuminate\Console\Scheduling;
use LaravelZero\Framework\Commands;

/**
 * This is the Laravel Zero Framework Bootstrapper Configuration class.
 *
 * Configures the console application.
 *
 * Takes in consideration the app name and the app version. Also
 * adds all the application commands.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
class Configurations extends Bootstrapper
{
    /**
     * {@inheritdoc}
     */
    public function bootstrap(): void
    {
        $config = $this->container->make('config');

        if ($name = $config->get('app.name')) {
            $this->application->setName($name);
        }

        if ($version = $config->get('app.version')) {
            $this->application->setVersion($version);
        }

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
