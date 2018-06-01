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
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use LaravelZero\Framework\Commands\Command;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * This is the Laravel Zero Framework Builder Command implementation.
 */
class Builder extends Command
{
    /**
     * {@inheritdoc}
     */
    protected $signature = 'app:build {name? : The build name}';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Compile your application into a single file';

    /**
     * Holds the configuration on is original state.
     *
     * @var string
     */
    protected static $config;

    /**
     * Holds the command original output.
     *
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected $originalOutput;

    /**
     * {@inheritdoc}
     */
    public function handle(): void
    {
        $this->title('Building process');

        $this->build($this->input->getArgument('name') ?? ARTISAN_BINARY);
    }

    /**
     * {@inheritdoc}
     */
    public function run(InputInterface $input, OutputInterface $output)
    {
        parent::run($input, $this->originalOutput = $output);
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
            ->clear();

        $this->output->writeln(
            sprintf('    Compiled successfully: <fg=green>%s</>', $this->app->buildsPath($name))
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
        if (! File::exists($this->app->buildsPath())) {
            File::makeDirectory($this->app->buildsPath());
        }

        $process = new Process(
            './box compile'
                .' --working-dir='.base_path()
                .' --config='.base_path('box.json'),
            dirname(dirname(dirname(__DIR__))).'/bin'
        );

        $section = $this->originalOutput->section();
        $section->write('');

        $progressBar = new ProgressBar(
            $this->output->getVerbosity() > OutputInterface::VERBOSITY_NORMAL ?
                new NullOutput() : $section,
            25
        );

        $progressBar->setProgressCharacter("\xF0\x9F\x8D\xBA");

        foreach (tap($process)->start() as $type => $data) {
            $progressBar->advance();

            if ($this->output->getVerbosity() > OutputInterface::VERBOSITY_NORMAL) {
                $process::OUT === $type ? $this->info("$data") : $this->error("$data");
            }
        }

        $progressBar->finish();

        $section->clear();

        $this->task('   2. <fg=yellow>Compile</> into a single file');

        $this->output->newLine();

        File::move($this->app->basePath(ARTISAN_BINARY) . '.phar', $this->app->buildsPath($name));

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

        $this->task('   1. Moving application to <fg=yellow>production mode</>', function () use ($file, $config) {
            File::put($file, '<?php return '.var_export($config, true).';'.PHP_EOL);
        });

        return $this;
    }

    /**
     * @return $this
     */
    protected function clear(): Builder
    {
        File::put($this->app->configPath('app.php'), static::$config);

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
