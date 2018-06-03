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

use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Components\AbstractComponentProvider;

/**
 * This is the Laravel Zero Framework Database Component Provider Implementation.
 */
class Provider extends AbstractComponentProvider
{
    /**
     * {@inheritdoc}
     */
    public function isAvailable(): bool
    {
        return class_exists(\Illuminate\Database\DatabaseServiceProvider::class)
            && is_array(config('database', false));
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
     * {@inheritdoc}
     */
    public function boot(): void
    {
        if ($this->app->environment() !== 'production') {
            $this->commands([
                \Illuminate\Database\Console\Migrations\MigrateMakeCommand::class,
                \Illuminate\Database\Console\Seeds\SeederMakeCommand::class,
                \Illuminate\Foundation\Console\ModelMakeCommand::class,
                \Illuminate\Database\Console\Seeds\SeedCommand::class,
            ]);
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

    /**
     * Registers the database service.
     *
     * Makes this Capsule Instance available globally via static methods.
     *
     * @return void
     */
    protected function registerDatabaseService(): void
    {
        $this->app['config']->set('database.migrations', 'migrations');
        $this->app->alias('db', \Illuminate\Database\ConnectionResolverInterface::class);

        $this->app->register(\Illuminate\Database\DatabaseServiceProvider::class);

        $this->app->make(\Illuminate\Database\Capsule\Manager::class)
            ->setAsGlobal();

        if (File::exists($this->app->databasePath('seeds'))) {
            collect(File::files($this->app->databasePath('seeds')))->each(
                function ($file) {
                    File::requireOnce($file);
                }
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
        $config = $this->app['config'];
        $config->set('database.migrations', $config->get('database.migrations') ?: 'migrations');
        $this->app->register(\Illuminate\Database\MigrationServiceProvider::class);

        $this->app->alias(
            'migration.repository',
            \Illuminate\Database\Migrations\MigrationRepositoryInterface::class
        );

        $this->app->singleton('migrator', function ($app) {
            $repository = $app['migration.repository'];

            return new Migrator($repository, $app['db'], $app['files']);
        });

        $this->app->alias(
            'migrator',
            \Illuminate\Database\Migrations\Migrator::class
        );
    }
}
