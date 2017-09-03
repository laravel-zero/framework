<?php

/**
 * This file is part of Zero Framework.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace LaravelZero\Framework\Commands\Component\Illuminate\Database;

use LaravelZero\Framework\Commands\Component\Installer as InstallCommand;
use LaravelZero\Framework\Contracts\Commands\Component\Installer as InstallerContract;

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
        $command->require('illuminate/database "5.4.*"');

        $command->info('Creating (database/database.sqlite)...');
        shell_exec('cd '.BASE_PATH.'&& mkdir database && touch database/database.sqlite');

        $command->info('Component installed! Usage:');
        $command->comment(
            '

            use Illuminate\Database\Capsule\Manager as DB;

            DB::schema()->create(\'users\', function ($table) {
                $table->increments(\'id\');
                $table->string(\'email\')->unique();
                $table->timestamps();
            });

            DB::table(\'users\')->insert(
                [\'email\' => \'enunomaduro@gmail.com\']
            );
        '
        );

        return true;
    }
}
