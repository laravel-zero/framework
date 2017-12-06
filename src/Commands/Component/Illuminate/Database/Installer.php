<?php

namespace LaravelZero\Framework\Commands\Component\Illuminate\Database;

use LaravelZero\Framework\Commands\Component\Installer as BaseInstaller;
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
     * The config file path.
     */
    const CONFIG_FILE = __DIR__.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'database.php';

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
        $this->require('illuminate/database "5.5.*"');
        $this->task(
            'Creating directories and files under database folder',
            function () {
                $this->files->makeDirectory(database_path('migrations'), 0755, true, true);
                if (! $this->files->exists(database_path('database.sqlite'))) {
                    $this->files->put(database_path('database.sqlite'), '');
                }
                $this->files->makeDirectory(database_path('seeds'), 0755, false, true);
                if (! $this->files->exists(database_path('seeds').DIRECTORY_SEPARATOR.'DatabaseSeeder.php')) {
                    $this->files->copy(
                        __DIR__.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'DatabaseSeeder.php',
                        database_path('seeds').DIRECTORY_SEPARATOR.'DatabaseSeeder.php'
                    );
                }

                return true;
            }
        );

        $this->task(
            'Creating default config',
            function () {
                if (! $this->files->exists(config_path('database.php'))) {
                    $this->files->copy(
                        static::CONFIG_FILE,
                        config_path('database.php')
                    );
                }

                return true;
            }
        );

        $this->info('Usage:');
        $this->comment(
            '

$ php <your-application-name> make:migration create_users_table
$ php <your-application-name> migrate

use Illuminate\Support\Facades\DB;

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
