<?php

namespace LaravelZero\Framework\Commands\Component\Illuminate\Database;

use LaravelZero\Framework\Commands\Component\AbstractComponentProvider;

/**
 * This is the Laravel Zero Framework illuminate/database component provider class.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
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
        if (file_exists(config_path('database.php'))) {
            $this->mergeConfigFrom(config_path('database.php'), 'database');
        }

        $this->registerDatabaseService();

        $this->registerMigrationService();
    }

    /**
     * Registers the database service.
     *
     * Makes this Capsule instance available globally via static methods.
     *
     * @return void
     */
    protected function registerDatabaseService(): void
    {
        $this->registerServiceProvider(\Illuminate\Database\DatabaseServiceProvider::class);

        $this->app->alias('db', \Illuminate\Database\DatabaseManager::class);
        $this->app->alias('db', \Illuminate\Database\ConnectionResolverInterface::class);
        $this->app->alias('db.connection', \Illuminate\Database\DatabaseManager::class);
        $this->app->alias('db.connection', \Illuminate\Database\ConnectionInterface::class);

        $this->app->make(\Illuminate\Database\Capsule\Manager::class)->setAsGlobal();

        if (config('database.with-seeds', true)) {
            if ($this->app->environment() !== 'production') {
                $this->commands([\Illuminate\Database\Console\Seeds\SeederMakeCommand::class]);
            }

            $this->commands([\Illuminate\Database\Console\Seeds\SeedCommand::class]);

            if (is_dir(database_path('seeds'))) {
                collect(
                    $this->app->make('files')->files(database_path('seeds'))
                )->each(
                    function ($file) {
                        $this->app->make('files')->requireOnce($file);
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
        $config = $this->app->make('config');
        $config->set('database.migrations', $config->get('database.migrations') ?: 'migrations');
        $this->registerServiceProvider(\Illuminate\Database\MigrationServiceProvider::class);
        $this->app->alias(
            'migration.repository',
            \Illuminate\Database\Migrations\MigrationRepositoryInterface::class
        );

        if (config('database.with-migrations', true)) {
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
