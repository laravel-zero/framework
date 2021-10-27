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

namespace LaravelZero\Framework\Components;

use Illuminate\Filesystem\Filesystem;
use LaravelZero\Framework\Commands\Command;
use LaravelZero\Framework\Contracts\Commands\Component\InstallerContract;
use LaravelZero\Framework\Contracts\Providers\ComposerContract;

/**
 * @internal
 */
abstract class AbstractInstaller extends Command implements InstallerContract
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
     * @var \LaravelZero\Framework\Contracts\Providers\ComposerContract
     */
    protected $composer;

    /**
     * AbstractInstaller constructor.
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
    public function handle()
    {
        $this->install();
    }

    /**
     * Requires the provided package.
     *
     * @param  string  $package
     * @param  bool  $dev
     * @return \LaravelZero\Framework\Contracts\Commands\Component\InstallerContract
     */
    protected function require(string $package, bool $dev = false): InstallerContract
    {
        $this->task(
            'Require package via composer',
            function () use ($package, $dev) {
                return $this->composer->require($package, $dev);
            }
        );

        return $this;
    }

    /**
     * Removes the provided package.
     *
     * @param  string  $package
     * @param  bool  $dev
     * @return \LaravelZero\Framework\Contracts\Commands\Component\InstallerContract
     */
    protected function remove(string $package, bool $dev = false): InstallerContract
    {
        $this->task(
            'Remove package via composer',
            function () use ($package, $dev) {
                return $this->composer->remove($package, $dev);
            }
        );

        return $this;
    }
}
