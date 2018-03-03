<?php

/**
 * This file is part of Laravel Zero.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace LaravelZero\Framework\Providers\Composer;

use Throwable;
use Symfony\Component\Process\Process;
use Illuminate\Contracts\Foundation\Application;
use Symfony\Component\Console\Output\OutputInterface;
use LaravelZero\Framework\Contracts\Providers\Composer as ComposerContract;

/**
 * This is the Laravel Zero Framework Composer implementation.
 */
class Composer implements ComposerContract
{
    /**
     * Holds an instance of the app.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * Composer constructor.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * {@inheritdoc}
     */
    public function install(array $options = []): bool
    {
        $cmd = "composer install";

        collect($options)->each(function ($option) use (&$cmd) {
            $cmd .= " $option";
        });

        return $this->run($cmd, $this->app->basePath());
    }

    /**
     * {@inheritdoc}
     */
    public function require(string $package): bool
    {
        return $this->run("composer require $package", $this->app->basePath());
    }

    /**
     * {@inheritdoc}
     */
    public function createProject(string $package, string $name, array $options): bool
    {
        $cmd = "composer create-project $package $name";

        collect($options)->each(function ($option) use (&$cmd) {
            $cmd .= " $option";
        });

        return $this->run($cmd);
    }

    /**
     * Runs the provided command.
     *
     * @param  string $cmd
     *
     * @return bool
     */
    private function run(string $cmd, string $cwd = null): bool
    {
        $process = new Process($cmd, $cwd);

        if ('\\' !== DIRECTORY_SEPARATOR && file_exists('/dev/tty') && is_readable('/dev/tty')) {
            $process->setTty(true);
        }

        try {
            $output = $this->app->make(OutputInterface::class);
        } catch (Throwable $e) {
            $output = null;
        }

        if ($output) {
            $output->write("\n");
            $process->run(function ($type, $line) use ($output) {
                $output->write($line);
            });
        } else {
            $process->run();
        }

        return $process->isSuccessful();
    }
}
