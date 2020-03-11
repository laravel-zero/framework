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

use function class_exists;
use function collect;
use Illuminate\Database\Migrations\MigrationCreator;
use Illuminate\Support\Facades\File;
use function is_array;
use LaravelZero\Framework\Components\AbstractComponentProvider;

/**
 * @internal
 */
class Provider extends AbstractComponentProvider
{
    /**
     * {@inheritdoc}
     */
    public function isAvailable(): bool
    {
        return class_exists(\Illuminate\Database\DatabaseServiceProvider::class)
            && is_array($this->app['config']->get('database', false))
            && $this->app['config']->get('database.useDefaultProvider', true) === true;
    }

    /**
     * {@inheritdoc}
     */
    public function register(): void
    {
        if (File::exists($this->app->configPath('database.php'))) {
            $this->mergeConfigFrom($this->app->configPath('database.php'), 'database');
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
            $this->commands(
                [
                    \Illuminate\Database\Console\Migrations\MigrateMakeCommand::class,
                    \Illuminate\Database\Console\Factories\FactoryMakeCommand::class,
                    \Illuminate\Database\Console\Seeds\SeederMakeCommand::class,
                    \Illuminate\Foundation\Console\ModelMakeCommand::class,
                    \Illuminate\Database\Console\Seeds\SeedCommand::class,
                ]
            );
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
                \Illuminate\Database\Console\WipeCommand::class,
            ]
        );
    }

    /**
     * Registers the database service.
     *
     * Makes this Capsule Instance available globally via static methods.
     */
    protected function registerDatabaseService(): void
    {
        $this->app->alias('db', \Illuminate\Database\ConnectionResolverInterface::class);
        $this->app->register(\Illuminate\Database\DatabaseServiceProvider::class);

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
     */
    protected function registerMigrationService(): void
    {
        $config = $this->app['config'];
        $config->set('database.migrations', $config->get('database.migrations') ?: 'migrations');
        $this->app->register(\Illuminate\Foundation\Providers\ComposerServiceProvider::class);
        $this->app->register(\Illuminate\Database\MigrationServiceProvider::class);
        $this->app->alias('migration.creator', MigrationCreator::class);

        $this->app->alias(
            'migration.repository',
            \Illuminate\Database\Migrations\MigrationRepositoryInterface::class
        );

        $this->app->singleton(
            'migrator',
            function ($app) {
                $repository = $app['migration.repository'];

                return new Migrator($repository, $app['db'], $app['files']);
            }
        );

        $this->app->alias(
            'migrator',
            \Illuminate\Database\Migrations\Migrator::class
        );
    }
}
