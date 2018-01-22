<?php

namespace LaravelZero\Framework\Commands\App;

use Phar;
use FilesystemIterator;
use UnexpectedValueException;
use LaravelZero\Framework\Commands\Command;

/**
 * This is the Laravel Zero Framework builder command class.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
class Builder extends Command
{
    /**
     * Contains the default app structure.
     *
     * @var []string
     */
    protected $structure = [
        'app' . DIRECTORY_SEPARATOR,
        'bootstrap' . DIRECTORY_SEPARATOR,
        'vendor' . DIRECTORY_SEPARATOR,
        'config' . DIRECTORY_SEPARATOR,
        'composer.json',
    ];

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
        $this->alert('Building the application...');

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
     * Builds the application.
     *
     * @param string $name
     *
     * @return $this
     */
    protected function build(string $name): Builder
    {
        $this->prepare()->compile($name)->cleanUp($name)->setPermissions($name)->finish();

        $this->info('Standalone application compiled into: builds' . DIRECTORY_SEPARATOR . $name);

        return $this;
    }

    /**
     * Compiles the standalone application.
     *
     * @param string $name
     *
     * @return $this
     */
    protected function compile(string $name): Builder
    {
        $this->info('Compiling code...');

        $compiler = $this->makeFolder()->getCompiler($name);

        $structure = config('app.structure') ?: $this->structure;

        $regex = '#' . implode('|', $structure) . '#';

        if (stristr(PHP_OS, 'WINNT') !== false) {
            $compiler->buildFromDirectory(BASE_PATH, str_replace('\\', '/', $regex));
        } else {
            // Linux, OS X
            $compiler->buildFromDirectory(BASE_PATH, $regex);
        }
        $compiler->setStub(
            "#!/usr/bin/env php \n" . $compiler->createDefaultStub('bootstrap' . DIRECTORY_SEPARATOR . 'init.php')
        );

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
                $this->app->buildsPath . DIRECTORY_SEPARATOR . $name . '.phar',
                FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::KEY_AS_FILENAME,
                $name
            );
        } catch (UnexpectedValueException $e) {
            $this->error("You can't perform a build.");
            exit(0);
        }
    }

    /**
     * Creates the folder for the builds.
     *
     * @return $this
     */
    protected function makeFolder(): Builder
    {
        if (! file_exists($this->app->buildsPath)) {
            mkdir($this->app->buildsPath);
        }

        return $this;
    }

    /**
     * Moves the compiled files to the builds folder.
     *
     * @param string $name
     *
     * @return $this
     */
    protected function cleanUp(string $name): Builder
    {
        $file = $this->app->buildsPath . DIRECTORY_SEPARATOR . $name;
        rename("$file.phar", $file);

        return $this;
    }

    /**
     * Sets the executable mode on the standalone application file.
     *
     * @param string $name
     *
     * @return $this
     */
    protected function setPermissions($name): Builder
    {
        $file = $this->app->buildsPath . DIRECTORY_SEPARATOR . $name;
        chmod($file, 0755);

        return $this;
    }

    /**
     * Prepares the application for build.
     *
     * @return $this
     */
    protected function prepare(): Builder
    {
        $file = BASE_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'app.php';
        static::$config = file_get_contents($file);
        $config = include $file;

        $config['production'] = true;

        $this->info('Moving configuration to production mode...');

        file_put_contents($file, '<?php return ' . var_export($config, true) . ';' . PHP_EOL);

        return $this;
    }

    /**
     * Prepares the application for build.
     *
     * @return $this
     */
    protected function finish(): Builder
    {
        file_put_contents(
            BASE_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'app.php',
            static::$config
        );

        static::$config = null;

        return $this;
    }

    /**
     * Makes sure that the finish is performed even
     * if the command fails.
     */
    public function __destruct()
    {
        if (static::$config !== null) {
            file_put_contents(
                BASE_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'app.php',
                static::$config
            );
        }
    }
}
