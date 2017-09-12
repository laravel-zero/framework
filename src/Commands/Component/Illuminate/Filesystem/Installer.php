<?php

/**
 * This file is part of Zero Framework.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace LaravelZero\Framework\Commands\Component\Illuminate\Filesystem;

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

        $command->info('Creating dummy assets directory...');
        shell_exec('cd '.BASE_PATH.'&& mkdir assets');

        $command->info('Component installed & assets dir created in the project root! Example usage in command classes:');
        $command->comment(
            '

            use Illuminate\Filesystem\Filesystem as Storage;

            Storage::put("assets/file.txt", "Thank you for considering Laravel Zero.");
        '
        );

        return true;
    }
}
