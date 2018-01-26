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

use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Component\AbstractComponentProvider;

/**
 * This is the Laravel Zero Framework Database Component Provider Implementation.
 */
class ComponentProvider extends AbstractComponentProvider
{
    /**
     * {@inheritdoc}
     */
    public function isAvailable(): bool
    {
        return class_exists(\Illuminate\Database\DatabaseServiceProvider::class);
    }

    /**
     * {@inheritdoc}
     */
    public function register(): void
    {
        if (File::exists(config_path('database.php'))) {
            $this->mergeConfigFrom(config_path('database.php'), 'database');
        }

        $this->registerDatabaseService();

        $this->registerMigrationService();
    }

    /**
     * Registers the database service.
     *
     * Makes this Capsule Instance available globally via static methods.
     *
     * @return void
     */
    protected function registerDatabaseService(): void
    {
        $this->app->register(\Illuminate\Database\DatabaseServiceProvider::class);

        $this->app->alias('db', \Illuminate\Database\DatabaseManager::class);
        $this->app->alias('db', \Illuminate\Database\ConnectionResolverInterface::class);
        $this->app->alias('db.connection', \Illuminate\Database\DatabaseManager::class);
        $this->app->alias('db.connection', \Illuminate\Database\ConnectionInterface::class);

        $this->app->make(\Illuminate\Database\Capsule\Manager::class)->setAsGlobal();

        if ($this->app['config']->get('database.with-seeds', true)) {
            if ($this->app->environment() !== 'production') {
                $this->commands([\Illuminate\Database\Console\Seeds\SeederMakeCommand::class]);
            }

            $this->commands([\Illuminate\Database\Console\Seeds\SeedCommand::class]);

            if (File::exists($this->app->databasePath('seeds'))) {
                collect(
                    File::files($this->app->databasePath('seeds'))
                )->each(
                    function ($file) {
                        File::requireOnce($file);
                    }
                );
            }
        }
    }

    /**
     * Registers the migration service.
     *
     * @return void
     */
    protected function registerMigrationService(): void
    {
        $config = $this->app['config'];
        $config->set('database.migrations', $config->get('database.migrations') ?: 'migrations');

        $this->app->register(\Illuminate\Database\MigrationServiceProvider::class);
        $this->app->alias(
            'migration.repository',
            \Illuminate\Database\Migrations\MigrationRepositoryInterface::class
        );

        if ($config->get('database.with-migrations', true)) {
            if ($this->app->environment() !== 'production') {
                $this->commands([\Illuminate\Database\Console\Migrations\MigrateMakeCommand::class]);
            }

            $this->commands(
                [
                    \Illuminate\Database\Console\Migrations\FreshCommand::class,
                    \Illuminate\Database\Console\Migrations\InstallCommand::class,
                    \Illuminate\Database\Console\Migrations\MigrateCommand::class,
                    \Illuminate\Database\Console\Migrations\RefreshCommand::class,
                    \Illuminate\Database\Console\Migrations\ResetCommand::class,
                    \Illuminate\Database\Console\Migrations\RollbackCommand::class,
                    \Illuminate\Database\Console\Migrations\StatusCommand::class,
                ]
            );
        }
    }
}
