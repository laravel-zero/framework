<?php

namespace LaravelZero\Framework\Commands\Component\Illuminate\Database;

use LaravelZero\Framework\Commands\Component\AbstractComponentProvider;

/**
 * This is the Zero Framework illuminate/database component provider class.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
class ComponentProvider extends AbstractComponentProvider
{
    /**
     * {@inheritdoc}
     */
    public function register(): void
    {
        if (! class_exists(\Illuminate\Database\DatabaseServiceProvider::class) || ! class_exists(
                \Illuminate\Filesystem\FilesystemServiceProvider::class
            )) {
            return;
        }

        $this->registerServiceProvider(\Illuminate\Database\DatabaseServiceProvider::class);

        $this->app->alias('db', \Illuminate\Database\DatabaseManager::class);
        $this->app->alias('db', \Illuminate\Database\ConnectionResolverInterface::class);
        $this->app->alias('db.connection', \Illuminate\Database\DatabaseManager::class);
        $this->app->alias('db.connection', \Illuminate\Database\ConnectionInterface::class);

        // Make this Capsule instance available globally via static methods
        $this->app->make(\Illuminate\Database\Capsule\Manager::class)
            ->setAsGlobal();

        $this->registerServiceProvider(\Illuminate\Database\MigrationServiceProvider::class);
        $this->app->alias('migration.repository', \Illuminate\Database\Migrations\MigrationRepositoryInterface::class);

        if ($this->app->make('config')
            ->get('database.migrations')) {
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
