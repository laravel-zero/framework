<?php

namespace LaravelZero\Framework\Commands\Component\Illuminate\Cache;

use LaravelZero\Framework\Commands\Component\Installer as InstallCommand;
use LaravelZero\Framework\Contracts\Providers\Composer as ComposerContract;
use LaravelZero\Framework\Contracts\Commands\Component\Installer as InstallerContract;

/**
 * This is the Zero Framework illuminate/cache install class.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
class Installer implements InstallerContract
{
    /**
     * {@inheritdoc}
     */
    public function install(InstallCommand $command, ComposerContract $composer): bool
    {
        $command->info('Pulling illuminate/cache...');
        $composer->require('illuminate/cache "5.5.*"');

        return true;
    }
}
