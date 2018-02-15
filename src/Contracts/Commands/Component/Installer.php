<?php

/**
 * This file is part of Laravel Zero.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace LaravelZero\Framework\Contracts\Commands\Component;

/**
 * This is the Laravel Zero Framework Component Installer Contract.
 */
interface Installer
{
    /**
     * Installs a component.
     *
     * @return void
     */
    public function install(): void;
}
