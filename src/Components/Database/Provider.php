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

use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Database\Console\Factories\FactoryMakeCommand;
use Illuminate\Database\Console\Migrations\FreshCommand;
use Illuminate\Database\Console\Migrations\InstallCommand;
use Illuminate\Database\Console\Migrations\MigrateCommand;
use Illuminate\Database\Console\Migrations\MigrateMakeCommand;
use Illuminate\Database\Console\Migrations\RefreshCommand;
use Illuminate\Database\Console\Migrations\ResetCommand;
use Illuminate\Database\Console\Migrations\RollbackCommand;
use Illuminate\Database\Console\Migrations\StatusCommand;
use Illuminate\Database\Console\Seeds\SeedCommand;
use Illuminate\Database\Console\Seeds\SeederMakeCommand;
use Illuminate\Database\Console\WipeCommand;
use Illuminate\Database\DatabaseServiceProvider;
use Illuminate\Database\Migrations\MigrationCreator;
use Illuminate\Database\Migrations\MigrationRepositoryInterface;
use Illuminate\Database\MigrationServiceProvider;
use Illuminate\Foundation\Console\ModelMakeCommand;
use Illuminate\Foundation\Providers\ComposerServiceProvider;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Components\AbstractComponentProvider;
use SplFileInfo;

use function class_exists;
use function collect;
use function is_array;

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
        return class_exists(DatabaseServiceProvider::class)
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
                    MigrateMakeCommand::class,
                    FactoryMakeCommand::class,
                    SeederMakeCommand::class,
                    ModelMakeCommand::class,
                    SeedCommand::class,
                ]
            );
        }

        $this->commands(
            [
                FreshCommand::class,
                InstallCommand::class,
                MigrateCommand::class,
                RefreshCommand::class,
                ResetCommand::class,
                RollbackCommand::class,
                StatusCommand::class,
                WipeCommand::class,
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
        $this->app->alias('db', ConnectionResolverInterface::class);
        $this->app->register(DatabaseServiceProvider::class);

        if (File::exists($this->app->databasePath('seeders'))) {
            collect(File::files($this->app->databasePath('seeders')))->each(
                fn (SplFileInfo $file) => File::requireOnce((string) $file)
            );
        }
    }

    /**
     * Registers the migration service.
     */
    protected function registerMigrationService(): void
    {
        /** @var Repository $config */
        $config = $this->app['config'];
        $config->set('database.migrations', $config->get('database.migrations') ?: 'migrations');

        $this->app->register(ComposerServiceProvider::class);
        $this->app->register(MigrationServiceProvider::class);
        $this->app->alias('migration.creator', MigrationCreator::class);

        $this->app->alias(
            'migration.repository',
            MigrationRepositoryInterface::class
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
