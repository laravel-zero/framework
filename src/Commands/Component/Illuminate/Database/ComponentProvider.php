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

        $this->app->make(\Illuminate\Database\Capsule\Manager::class)
            ->setAsGlobal();

        if ($this->app->make('config')
            ->get('database.with-seeds')) {
            $this->commands(
                [
                    \Illuminate\Database\Console\Seeds\SeedCommand::class,
                    \Illuminate\Database\Console\Seeds\SeederMakeCommand::class,
                ]
            );
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

        if ($this->app->make('config')
            ->get('database.with-migrations')) {
            $this->commands(
                [
                    \Illuminate\Database\Console\Migrations\FreshCommand::class,
                    \Illuminate\Database\Console\Migrations\InstallCommand::class,
                    \Illuminate\Database\Console\Migrations\MigrateCommand::class,
                    \Illuminate\Database\Console\Migrations\MigrateMakeCommand::class,
                    \Illuminate\Database\Console\Migrations\RefreshCommand::class,
                    \Illuminate\Database\Console\Migrations\ResetCommand::class,
                    \Illuminate\Database\Console\Migrations\RollbackCommand::class,
                    \Illuminate\Database\Console\Migrations\StatusCommand::class,
                ]
            );
        }
    }
}
