<?php

/**
 * This file is part of Laravel Zero.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace LaravelZero\Framework\Commands\Component\Illuminate\Database;

use LaravelZero\Framework\Commands\Component\AbstractInstaller;

/**
 * This is the Laravel Zero Framework Database Component Installer Implementation.
 */
class Installer extends AbstractInstaller
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
        $this->require('illuminate/database "5.6.*"');

        $this->task(
            'Creating a default SQLite database',
            function () {
                if (! $this->files->exists($this->app->databasePath('database.sqlite'))) {
                    return $this->files->put($this->app->databasePath('database.sqlite'), '');
                }

                return false;
            }
        );

        $this->task(
            'Creating seeds folders and files',
            function () {
                $this->files->makeDirectory($this->app->databasePath('seeds'), 0755, false, true);
                if (! $this->files->exists(
                    $this->app->databasePath('seeds'.DIRECTORY_SEPARATOR.'DatabaseSeeder.php')
                )) {
                    $this->files->copy(
                        __DIR__.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'DatabaseSeeder.php',
                        $this->app->databasePath('seeds'.DIRECTORY_SEPARATOR.'DatabaseSeeder.php')
                    );
                }

                return false;
            }
        );

        $this->task(
            'Creating default database configuration',
            function () {
                if (! $this->files->exists($this->app->configPath('database.php'))) {
                    return $this->files->copy(
                        static::CONFIG_FILE,
                        $this->app->configPath('database.php')
                    );
                }

                return false;
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
