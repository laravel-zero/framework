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

namespace LaravelZero\Framework\Contracts\Commands\Component;

interface InstallerContract
{
    /**
     * Installs a component.
     *
     * @return void
     */
    public function install(): void;
}
