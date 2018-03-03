<?php

/**
 * This file is part of Laravel Zero.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace LaravelZero\Framework\Commands\App;

use Phar;
use FilesystemIterator;
use UnexpectedValueException;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use LaravelZero\Framework\Commands\Command;
use LaravelZero\Framework\Contracts\Providers\Composer;

/**
 * This is the Laravel Zero Framework Builder Command implementation.
 */
class Builder extends Command
{
    /**
     * Contains the default app structure.
     *
     * @var string[]
     */
    protected $structure = [
        'app' . DIRECTORY_SEPARATOR,
        'bootstrap' . DIRECTORY_SEPARATOR,
        'vendor' . DIRECTORY_SEPARATOR,
        'config' . DIRECTORY_SEPARATOR,
        'composer.json',
        'builder-stub',
        '.env'
    ];

    /**
     * Holds the stub name.
     *
     * @var string
     */
    protected $stub = 'builder-stub';

    /**
     * {@inheritdoc}
     */
    protected $signature = 'app:build {name=application : The build name}';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Perform an application build';

    /**
     * Holds the configuration on is original state.
     *
     * @var string
     */
    protected static $config;

    /**
     * {@inheritdoc}
     */
    public function handle(): void
    {
        $this->info('Building the application...');

        if (Phar::canWrite()) {
            $this->build($this->input->getArgument('name') ?: static::BUILD_NAME);
        } else {
            $this->error(
                'Unable to compile a phar because of php\'s security settings. ' . 'phar.readonly must be disabled in php.ini. ' . PHP_EOL . PHP_EOL . 'You will need to edit ' . php_ini_loaded_file(
                ) . ' and add or set' . PHP_EOL . PHP_EOL . '    phar.readonly = Off' . PHP_EOL . PHP_EOL . 'to continue. Details here: http://php.net/manual/en/phar.configuration.php'
            );
        }
    }

    /**
     * Builds the application into a single file.
     *
     * @param string $name The file name.
     *
     * @return $this
     */
    protected function build(string $name): Builder
    {
        /*
         * We prepare the application for a build, moving it to production. Then,
         * after compile all the code to a single file, we move the built file
         * to the builds folder with the correct permissions.
         */
        $this->prepare()
            ->compile($name)
            ->setPermissions($name)
            ->clear();

        $this->info(
            sprintf('Application built into a single file: %s', $this->app->buildsPath($name))
        );

        return $this;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    protected function compile(string $name): Builder
    {
        $compiler = $this->makeBuildsFolder()
            ->getCompiler($name);

        $structure = config('app.structure') ?: $this->structure;

        $regex = '#' . implode('|', $structure) . '#';

        if (stristr(PHP_OS, 'WINNT') !== false) { // For windows:
            $compiler->buildFromDirectory($this->app->basePath(), str_replace('\\', '/', $regex));
        } else { // Linux, OS X:
            $compiler->buildFromDirectory($this->app->basePath(), $regex);
        }

        $this->task('Compiling code', function () use ($compiler) {
            $compiler->setStub(
                "#!/usr/bin/env php \n" . $compiler->createDefaultStub(
                    $this->stub
                )
            );
        });

        $file = $this->app->buildsPath($name);

        File::move("$file.phar", $file);

        return $this;
    }

    /**
     * Gets a new instance of the compiler.
     *
     * @param string $name
     *
     * @return \Phar
     */
    protected function getCompiler(string $name): \Phar
    {
        try {
            return new Phar(
                $this->app->buildsPath($name . '.phar'),
                FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::KEY_AS_FILENAME,
                $name
            );
        } catch (UnexpectedValueException $e) {
            $this->app->abort(401, 'Unauthorized.');
        }
    }

    /**
     * @return $this
     */
    protected function makeBuildsFolder(): Builder
    {
        if (! File::exists($this->app->buildsPath())) {
            File::makeDirectory($this->app->buildsPath());
        }

        return $this;
    }

    /**
     * Sets the executable mode on the single application file.
     *
     * @param string $name
     *
     * @return $this
     */
    protected function setPermissions($name): Builder
    {
        chmod($this->app->buildsPath($name), 0755);

        return $this;
    }

    /**
     * @return $this
     */
    protected function prepare(): Builder
    {
        $file = $this->app->configPath('app.php');
        static::$config = File::get($file);
        $config = include $file;

        $config['production'] = true;

        $this->task('Moving application to production mode', function () use ($file, $config) {
            File::put($file, '<?php return ' . var_export($config, true) . ';' . PHP_EOL);
        });

        $this->task('Temporarily removing dev dependencies', function () {
            $this->app[Composer::class]->install(['--no-dev']);
        });

        $stub = str_replace('#!/usr/bin/env php', '', File::get($this->app->basePath(ARTISAN_BINARY)));

        File::put($this->app->basePath($this->stub), $stub);

        return $this;
    }

    /**
     * @return $this
     */
    protected function clear(): Builder
    {
        File::put($this->app->configPath('app.php'), static::$config);

        File::delete($this->app->basePath($this->stub));

        $this->task('Reinstalling dev dependencies', function () {
            $this->app[Composer::class]->install();
        });

        static::$config = null;

        return $this;
    }

    /**
     * Makes sure that the `clear` is performed even
     * if the command fails.
     *
     * @return void
     */
    public function __destruct()
    {
        if (static::$config !== null) {
            $this->clear();
            $this->error('Something went wrong.');
        }
    }
}
