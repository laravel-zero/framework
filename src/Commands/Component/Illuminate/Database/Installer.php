<?php

namespace LaravelZero\Framework\Commands\Component\Illuminate\Database;

use LaravelZero\Framework\Commands\Component\Installer as BaseInstaller;
use LaravelZero\Framework\Contracts\Providers\Composer as ComposerContract;
use LaravelZero\Framework\Contracts\Commands\Component\Installer as InstallerContract;

/**
 * This is the Laravel Zero Framework illuminate/database install class.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
class Installer extends BaseInstaller implements InstallerContract
{
    /**
     * {@inheritdoc}
     */
    protected $name = 'install:database';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Installs illuminate/database - Eloquent';

    /**
     * {@inheritdoc}
     */
    public function getComponentName(): string
    {
        return 'database';
    }

    /**
     * {@inheritdoc}
     */
    public function install(): bool
    {
        $composer = $this->getContainer()
            ->make(ComposerContract::class);

        $this->info('Pulling illuminate/database...');
        $composer->require('illuminate/database "5.5.*"');
        $composer->require('illuminate/filesystem "5.5.*"');

        $this->info('Creating (database/database.sqlite)...');
        shell_exec('cd '.BASE_PATH.'&& mkdir database && touch database'.DIRECTORY_SEPARATOR.'database.sqlite');
        shell_exec('cd '.BASE_PATH.DIRECTORY_SEPARATOR.'database && mkdir migrations');
        shell_exec('cd '.BASE_PATH.DIRECTORY_SEPARATOR.'database && mkdir seeds');

        $this->info('Copying default config...');
        shell_exec('cp -n '.__DIR__.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'database.php '.config_path('database.php'));

        $this->info('Create database seeder...');
        shell_exec('cp -n '.__DIR__.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'DatabaseSeeder.php '.database_path('seeds').DIRECTORY_SEPARATOR.'DatabaseSeeder.php');

        $this->info('Component installed! Usage:');
        $this->comment(
            '

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

        '
        );

        return true;
    }
}
