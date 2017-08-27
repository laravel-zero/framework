<?php

/**
 * This file is part of Zero Framework.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace NunoMaduro\ZeroFramework\Commands\Component\Illuminate\Database;

use NunoMaduro\ZeroFramework\Commands\Component\Installer as InstallCommand;
use NunoMaduro\ZeroFramework\Contracts\Commands\Component\Installer as InstallerContract;

/**
 * The is the Zero Framework illuminate/database install class.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
class Installer implements InstallerContract
{
    /**
     * {@inheritdoc}
     */
    public function install(InstallCommand $command): bool
    {
        $command->require('illuminate/database');

        $command->info("Creating (database/database.sqlite)...");
        shell_exec('cd '.BASE_PATH.'&& mkdir database && touch database/database.sqlite');

        $command->info("Component installed! Usage:");
        $command->comment(
            '
            
            use Illuminate\Database\Capsule\Manager as DB;
            
            DB::table(\'users\')->insert(
                [\'email\' => \'enunomaduro@gmail.com\']
            );
        '
        );

        return true;
    }
}
