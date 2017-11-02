<?php

namespace LaravelZero\Framework\Contracts\Commands\Component;

use LaravelZero\Framework\Commands\Component\Installer as InstallCommand;
use LaravelZero\Framework\Contracts\Providers\Composer as ComposerContract;

/**
 * This is the Laravel Zero Framework component install contract.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
interface Installer
{
    /**
     * Returns the component name.
     *
     * @return string
     */
    public function getComponentName(): string;

    /**
     * Installs the component and returns the result
     * of the installation.
     *
     * @return bool
     */
    public function install(): bool;
}
