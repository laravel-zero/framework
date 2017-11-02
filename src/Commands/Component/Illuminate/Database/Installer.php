<?php

namespace LaravelZero\Framework\Commands\Component\Illuminate\Database;

use LaravelZero\Framework\Commands\Component\Installer as InstallCommand;
use LaravelZero\Framework\Contracts\Providers\Composer as ComposerContract;
use LaravelZero\Framework\Contracts\Commands\Component\Installer as InstallerContract;

/**
 * This is the Laravel Zero Framework illuminate/database install class.
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
        $command->info('Pulling illuminate/database...');
        $composer->require('illuminate/database "5.5.*"');
        $composer->require('illuminate/filesystem "5.5.*"');

        $command->info('Creating (database/database.sqlite)...');
        shell_exec('cd '.BASE_PATH.'&& mkdir database && touch database/database.sqlite');
        shell_exec('cd '.BASE_PATH.'/database && mkdir migrations');

        $command->info('Copying default config...');
        shell_exec('cp -n '.__DIR__.'/config/database.php '.config_path('database.php'));

        $command->info('Component installed! Usage:');
        $command->comment('

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

Schema::create(\'users\', function ($table) {
    $table->increments(\'id\');
    $table->string(\'email\')->unique();
    $table->timestamps();
});

DB::table(\'users\')->insert(
    [\'email\' => \'enunomaduro@gmail.com\']
);

$users = DB::table(\'users\')->get();

dd($users);

        ');

        return true;
    }
}
