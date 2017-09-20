<?php

namespace LaravelZero\Framework\Commands\App;

use Phar;
use FilesystemIterator;
use UnexpectedValueException;
use LaravelZero\Framework\Commands\Command;
use Symfony\Component\Console\Input\InputArgument;

/**
 * This is the Zero Framework builder command class.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
class Builder extends Command
{
    /**
     * The directory that contains your application builds.
     */
    const BUILD_PATH = BASE_PATH.'/builds';

    /**
     * The default build name.
     */
    const BUILD_NAME = 'application';

    /**
     * Contains the application structure
     * needed for the build.
     *
     * @var array
     */
    protected $structure = [
        'app/',
        'bootstrap/',
        'vendor/',
        'config/',
    ];

    /**
     * The name of the console command.
     *
     * @var string
     */
    protected $name = 'app:build';

    /**
     * The console command description.
     *
     * @var string
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
            $this->build($this->input->getArgument('name') ?: self::BUILD_NAME);
        } else {
            $this->error(
                'Unable to compile a phar because of php\'s security settings. '.'phar.readonly must be disabled in php.ini. '.PHP_EOL.PHP_EOL.'You will need to edit '.php_ini_loaded_file(
                ).' and add or set'.PHP_EOL.PHP_EOL.'    phar.readonly = Off'.PHP_EOL.PHP_EOL.'to continue. Details here: http://php.net/manual/en/phar.configuration.php'
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::OPTIONAL);
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
        $this->prepare()
            ->compile($name)
            ->cleanUp($name)
            ->setPermissions($name)
            ->finish();

        $this->info("Standalone application compiled into: builds/$name");

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

        $compiler = $this->makeFolder()
            ->getCompiler($name);

        $compiler->buildFromDirectory(BASE_PATH, '#'.implode('|', $this->structure).'#');
        $compiler->setStub("#!/usr/bin/env php \n".$compiler->createDefaultStub('bootstrap/init.php'));

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
                self::BUILD_PATH.'/'.$name.'.phar',
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
        if (! file_exists(self::BUILD_PATH)) {
            mkdir(self::BUILD_PATH);
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
        $file = self::BUILD_PATH."/$name";
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
        $file = self::BUILD_PATH."/$name";
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
        $file = BASE_PATH.'/config/config.php';
        static::$config = file_get_contents($file);
        $config = include $file;

        $config['app']['production'] = true;
        file_put_contents($file, '<?php return '.var_export($config, true).';'.PHP_EOL);

        $this->info('Moving configuration to production mode...');

        return $this;
    }

    /**
     * Prepares the application for build.
     *
     * @return $this
     */
    protected function finish(): Builder
    {
        file_put_contents(BASE_PATH.'/config/config.php', static::$config);

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
            file_put_contents(BASE_PATH.'/config/config.php', static::$config);
        }
    }
}
