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

namespace LaravelZero\Framework\Providers\Composer;

use Throwable;
use function collect;
use Symfony\Component\Process\Process;
use Illuminate\Contracts\Foundation\Application;
use Symfony\Component\Console\Output\OutputInterface;
use LaravelZero\Framework\Contracts\Providers\ComposerContract;

/**
 * @codeCoverageIgnore
 * @internal
 */
final class Composer implements ComposerContract
{
    /**
     * Holds an instance of the app.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    private $app;

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
        $cmd = 'composer install';

        collect($options)->each(
            function ($option) use (&$cmd) {
                $cmd .= " $option";
            }
        );

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
    public function createProject(string $skeleton, string $projectName, array $options): bool
    {
        $cmd = "composer create-project $skeleton $projectName";

        collect($options)->each(
            function ($option) use (&$cmd) {
                $cmd .= " $option";
            }
        );

        return $this->run($cmd);
    }

    /**
     * Runs the provided command on the provided folder.
     */
    private function run(string $cmd, string $cwd = null): bool
    {
        $process = new Process($cmd, $cwd);

        $process->setTimeout(300);

        if ($process->isTty()) {
            $process->setTty(true);
        }

        try {
            $output = $this->app->make(OutputInterface::class);
        } catch (Throwable $e) {
            $output = null;
        }

        if ($output) {
            $output->write("\n");
            $process->run(
                function ($type, $line) use ($output) {
                    $output->write($line);
                }
            );
        } else {
            $process->run();
        }

        return $process->isSuccessful();
    }
}
