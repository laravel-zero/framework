<?php

/**
 * This file is part of Laravel Zero.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace LaravelZero\Framework\Components\Database;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Components\AbstractInstaller;

/**
 * This is the Laravel Zero Framework Component Database Installer Implementation.
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
    const CONFIG_FILE = __DIR__.DIRECTORY_SEPARATOR.'stubs'.DIRECTORY_SEPARATOR.'database.php';

    /**
     * The seeder file.
     */
    const SEEDER_FILE = __DIR__.DIRECTORY_SEPARATOR.'stubs'.DIRECTORY_SEPARATOR.'DatabaseSeeder.php';

    /**
     * {@inheritdoc}
     */
    public function install(): void
    {
        $this->require('illuminate/database "5.6.*"');

        $this->task(
            'Creating a default SQLite database',
            function () {
                if (! File::exists(database_path('database.sqlite'))) {
                    File::makeDirectory(database_path('migrations'), 0755, true, true);

                    File::put(
                        database_path('database.sqlite'),
                        ''
                    );

                    return true;
                }

                return false;
            }
        );

        $this->task(
            'Creating seeds folders and files',
            function () {
                if (! File::exists(database_path('seeds'.DIRECTORY_SEPARATOR.'DatabaseSeeder.php'))) {
                    File::makeDirectory(database_path('seeds'), 0755, false, true);

                    File::copy(
                        static::SEEDER_FILE,
                        database_path('seeds'.DIRECTORY_SEPARATOR.'DatabaseSeeder.php')
                    );

                    return true;
                }

                return false;
            }
        );

        $this->task(
            'Creating default database configuration',
            function () {
                if (! File::exists(config_path('database.php'))) {
                    File::copy(
                        static::CONFIG_FILE,
                        config_path('database.php')
                    );

                    return true;
                }

                return false;
            }
        );

        $this->task(
            'Updating .gitignore',
            function () {
                $gitignorePath = base_path('.gitignore');
                if (File::exists($gitignorePath)) {
                    $contents = File::get($gitignorePath);
                    $neededLine = '/database/database.sqlite';
                    if (! Str::contains($contents, $neededLine)) {
                        File::append($gitignorePath, $neededLine.PHP_EOL);

                        return true;
                    }
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
        '
        );
    }
}
