<?php

namespace LaravelZero\Framework\Commands\Component\Vlucas\Phpdotenv;

use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Component\Installer as BaseInstaller;
use LaravelZero\Framework\Contracts\Commands\Component\Installer as InstallerContract;

/**
 * This is the Laravel Zero Framework vlucas/phpdotent install class.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
class Installer extends BaseInstaller implements InstallerContract
{
    /**
     * {@inheritdoc}
     */
    protected $name = 'install:dotenv';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Installs vlucas/phpdotenv';

    /**
     * {@inheritdoc}
     */
    public function getComponentName(): string
    {
        return 'dotenv';
    }

    /**
     * {@inheritdoc}
     */
    public function install(): bool
    {
        $this->require('vlucas/phpdotenv');

        $this->task(
            'Creating .env and .env.example',
            function () {
                if (! File::exists(base_path('.env'))) {
                    File::put(base_path('.env'), 'CONSUMER_KEY=');
                }

                if (! File::exists(base_path('.env.example'))) {
                    File::put(base_path('.env.example'), 'CONSUMER_KEY=');
                }

                return true;
            }
        );

        return true;
    }
}
