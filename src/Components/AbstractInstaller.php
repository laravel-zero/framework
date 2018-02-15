<?php

/**
 * This file is part of Laravel Zero.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace LaravelZero\Framework\Components;

use Illuminate\Filesystem\Filesystem;
use LaravelZero\Framework\Commands\Command;
use LaravelZero\Framework\Contracts\Commands\Component\Installer;
use LaravelZero\Framework\Contracts\Providers\Composer as ComposerContract;

/**
 * This is the Laravel Zero Framework Abstract Installer Implementation.
 */
abstract class AbstractInstaller extends Command implements Installer
{
    /**
     * Holds an instance of Files.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Holds an instance of composer.
     *
     * @var \LaravelZero\Framework\Contracts\Providers\Composer
     */
    protected $composer;

    /**
     * Installer constructor.
     *
     * @param \Illuminate\Filesystem\Filesystem $files
     * @param \LaravelZero\Framework\Contracts\Providers\Composer $composer
     */
    public function __construct(Filesystem $files, ComposerContract $composer)
    {
        parent::__construct();

        $this->files = $files;

        $this->composer = $composer;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(): void
    {
        $this->install();
    }

    /**
     * Requires the provided package.
     *
     * @param  string $package
     *
     * @return \LaravelZero\Framework\Contracts\Commands\Component\Installer
     */
    protected function require(string $package): Installer
    {
        $this->task(
            'Require package via composer',
            function () use ($package) {
                return $this->composer->require($package);
            }
        );

        return $this;
    }
}
