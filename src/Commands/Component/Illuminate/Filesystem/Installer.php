<?php

namespace LaravelZero\Framework\Commands\Component\Illuminate\filesystem;

use LaravelZero\Framework\Commands\Component\Installer as InstallCommand;
use LaravelZero\Framework\Contracts\Providers\Composer as ComposerContract;
use LaravelZero\Framework\Contracts\Commands\Component\Installer as InstallerContract;

/**
 * This is the Zero Framework illuminate/filesystem install class.
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
        $command->info('Pulling illuminate/filesystem...');
        $composer->require('illuminate/filesystem "5.5.*"');

        $command->info('Component installed! Usage:');
        $command->comment('

use Illuminate\Support\Facades\File;

File::put("file.txt", "Thank you for considering Laravel Zero.");

        ');

        return true;
    }
}
