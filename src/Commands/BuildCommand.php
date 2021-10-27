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

namespace LaravelZero\Framework\Commands;

use Illuminate\Console\Application as Artisan;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

final class BuildCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected $signature = 'app:build
                            {name? : The build name}
                            {--build-version= : The build version, if not provided it will be asked}
                            {--timeout=300 : The timeout in seconds or 0 to disable}';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Build a single file executable';

    /**
     * Holds the configuration on is original state.
     *
     * @var string|null
     */
    private static $config;

    /**
     * Holds the box.json on is original state.
     *
     * @var string|null
     */
    private static $box;

    /**
     * Holds the command original output.
     *
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    private $originalOutput;

    /**
     * {@inheritdoc}
     */
    public function handle()
    {
        if ($this->supportsAsyncSignals()) {
            $this->listenForSignals();
        }

        $this->title('Building process');

        $this->build($this->input->getArgument('name') ?? $this->getBinary());
    }

    /**
     * {@inheritdoc}
     */
    public function run(InputInterface $input, OutputInterface $output)
    {
        return parent::run($input, $this->originalOutput = $output);
    }

    /**
     * Builds the application into a single file.
     */
    private function build(string $name): BuildCommand
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

    private function compile(string $name): BuildCommand
    {
        if (! File::exists($this->app->buildsPath())) {
            File::makeDirectory($this->app->buildsPath());
        }

        $boxBinary = windows_os() ? '.\box.bat' : './box';

        $process = new Process(
            [$boxBinary, 'compile', '--working-dir='.base_path(), '--config='.base_path('box.json')] + $this->getExtraBoxOptions(),
            dirname(__DIR__, 2).'/bin',
            null,
            null,
            $this->getTimeout()
        );

        $section = tap($this->originalOutput->section())->write('');

        $progressBar = tap(
            new ProgressBar(
                $this->output->getVerbosity() > OutputInterface::VERBOSITY_NORMAL ? new NullOutput() : $section, 25
            )
        )->setProgressCharacter("\xF0\x9F\x8D\xBA");

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

        File::move($this->app->basePath($this->getBinary()).'.phar', $this->app->buildsPath($name));

        return $this;
    }

    private function prepare(): BuildCommand
    {
        $configFile = $this->app->configPath('app.php');
        static::$config = File::get($configFile);

        $config = include $configFile;

        $config['env'] = 'production';
        $version = $this->option('build-version') ?: $this->ask('Build version?', $config['version']);
        $config['version'] = $version;

        $boxFile = $this->app->basePath('box.json');
        static::$box = File::get($boxFile);

        $this->task(
            '   1. Moving application to <fg=yellow>production mode</>',
            function () use ($configFile, $config) {
                File::put($configFile, '<?php return '.var_export($config, true).';'.PHP_EOL);
            }
        );

        $boxContents = json_decode(static::$box, true);
        $boxContents['main'] = $this->getBinary();
        File::put($boxFile, json_encode($boxContents));

        File::put($configFile, '<?php return '.var_export($config, true).';'.PHP_EOL);

        return $this;
    }

    private function clear(): BuildCommand
    {
        File::put($this->app->configPath('app.php'), static::$config);

        File::put($this->app->basePath('box.json'), static::$box);

        static::$config = null;

        static::$box = null;

        return $this;
    }

    /**
     * Returns the artisan binary.
     */
    private function getBinary(): string
    {
        return str_replace(["'", '"'], '', Artisan::artisanBinary());
    }

    /**
     * Returns a valid timeout value. Non positive values are converted to null,
     * meaning no timeout.
     *
     * @return float|null
     *
     * @throws \InvalidArgumentException
     */
    private function getTimeout(): ?float
    {
        if (! is_numeric($this->option('timeout'))) {
            throw new \InvalidArgumentException('The timeout value must be a number.');
        }

        $timeout = (float) $this->option('timeout');

        return $timeout > 0 ? $timeout : null;
    }

    /**
     * Enable and listen to async signals for the process.
     */
    private function listenForSignals(): void
    {
        pcntl_async_signals(true);

        pcntl_signal(SIGINT, function () {
            if (static::$config !== null) {
                $this->clear();
            }

            exit;
        });
    }

    /**
     * Determine if "async" signals are supported.
     */
    private function supportsAsyncSignals(): bool
    {
        return extension_loaded('pcntl');
    }

    private function getExtraBoxOptions(): array
    {
        $extraBoxOptions = [];

        if ($this->output->isDebug()) {
            $extraBoxOptions[] = '--debug';
        }

        return $extraBoxOptions;
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
        }
    }
}
