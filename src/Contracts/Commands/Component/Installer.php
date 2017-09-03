<?php

/**
 * This file is part of Zero Framework.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace LaravelZero\Framework\Contracts\Commands\Component;

use LaravelZero\Framework\Commands\Component\Installer as InstallCommand;
use LaravelZero\Framework\Contracts\Providers\Composer as ComposerContract;

/**
 * This is the Zero Framework component install contract.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
interface Installer
{
    /**
     * Installs the component and returns the result
     * of the installation.
     *
     * @param \LaravelZero\Framework\Commands\Component\Installer $command
     * @param \LaravelZero\Framework\Contracts\Providers\Composer $command
     *
     * @return bool
     */
    public function install(InstallCommand $command, ComposerContract $composer): bool;
}
