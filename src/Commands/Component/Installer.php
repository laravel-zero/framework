<?php

namespace LaravelZero\Framework\Commands\Component;

use Illuminate\Filesystem\Filesystem;
use LaravelZero\Framework\Commands\Command;
use LaravelZero\Framework\Contracts\Providers\Composer as ComposerContract;
use LaravelZero\Framework\Contracts\Commands\Component\Installer as InstallerContract;

/**
 * This is the Laravel Zero Framework component installer command class.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
abstract class Installer extends Command implements InstallerContract
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
        $component = $this->getComponentName();

        $this->task(
            "The component $component installation",
            function () {
                return $this->install();
            }
        );
    }

    /**
     * Requires the provided package.
     *
     * @param  string $package
     *
     * @return \LaravelZero\Framework\Contracts\Commands\Component\Installer
     */
    protected function require(string $package): InstallerContract
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
