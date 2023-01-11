<?php

declare(strict_types=1);

/**
 * This file is part of Laravel Zero.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace LaravelZero\Framework\Components\Database;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use LaravelZero\Framework\Components\AbstractInstaller;

/**
 * @internal
 */
final class Installer extends AbstractInstaller
{
    /**
     * {@inheritdoc}
     */
    protected $name = 'install:database';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Eloquent ORM: Database layer';

    /**
     * The config file path.
     */
    private const CONFIG_FILE = __DIR__.DIRECTORY_SEPARATOR.'stubs'.DIRECTORY_SEPARATOR.'database.php';

    /**
     * The seeder file path.
     */
    private const SEEDER_FILE = __DIR__.DIRECTORY_SEPARATOR.'stubs'.DIRECTORY_SEPARATOR.'DatabaseSeeder.php';

    /**
     * {@inheritdoc}
     */
    public function install(): void
    {
        $this->require('illuminate/database "^10.0"');
        $this->require('fakerphp/faker "^1.9.1"', true);

        $this->task(
            'Creating a default SQLite database',
            function () {
                if (File::exists(database_path('database.sqlite'))) {
                    return false;
                }

                File::makeDirectory($this->app->databasePath(), 0755, true, true);

                return File::put(
                    $this->app->databasePath('database.sqlite'),
                    ''
                );
            }
        );

        $this->task(
            'Creating migrations folder',
            function () {
                if (File::exists($this->app->databasePath('migrations'))) {
                    return false;
                }

                return File::makeDirectory($this->app->databasePath('migrations'), 0755, true, true);
            }
        );

        $this->task(
            'Creating seeders folders and files',
            function () {
                if (File::exists($this->app->databasePath('seeders'.DIRECTORY_SEPARATOR.'DatabaseSeeder.php'))) {
                    return false;
                }

                File::makeDirectory($this->app->databasePath('seeders'), 0755, false, true);

                return File::copy(
                    self::SEEDER_FILE,
                    $this->app->databasePath('seeders'.DIRECTORY_SEPARATOR.'DatabaseSeeder.php')
                );
            }
        );

        $this->task(
            'Creating factories folder',
            function () {
                if (File::exists($this->app->databasePath('factories'))) {
                    return false;
                }

                return File::makeDirectory($this->app->databasePath('factories'), 0755, true, true);
            }
        );

        $this->task(
            'Creating default database configuration',
            function () {
                if (File::exists(config_path('database.php'))) {
                    return false;
                }

                return File::copy(
                    self::CONFIG_FILE,
                    config_path('database.php')
                );
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

use DB;

$users = DB::table(\'users\')->get();
        '
        );
    }
}
