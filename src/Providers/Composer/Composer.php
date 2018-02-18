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

use Symfony\Component\Process\Process;
use Illuminate\Contracts\Foundation\Application;
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
     * return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * {@inheritdoc}
     */
    public function require(string $package): bool
    {
        ($process = new Process("composer require $package", $this->app->basePath()))->run();

        return $process->isSuccessful();
    }

    /**
     * {@inheritdoc}
     */
    public function createProject(string $package, string $name, array $options): bool
    {
        $cmd = "composer create-project $package $name";

        collect($options)->each(
            function ($option) use (&$cmd) {
                $cmd .= " $option";
            }
        );

        ($process = new Process($cmd))->start();

        $process->wait();

        return $process->isSuccessful();
    }
}
