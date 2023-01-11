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

namespace LaravelZero\Framework\Components\Updater;

use LaravelZero\Framework\Components\AbstractInstaller;

/**
 * @internal
 */
final class Installer extends AbstractInstaller
{
    /**
     * {@inheritdoc}
     */
    protected $name = 'install:self-update';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Self-update: Allows to self-update a build application';

    /**
     * {@inheritdoc}
     */
    public function install(): void
    {
        $this->require('laravel-zero/phar-updater "^1.3"');
    }
}
