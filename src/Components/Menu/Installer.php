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

namespace LaravelZero\Framework\Components\Menu;

use LaravelZero\Framework\Components\AbstractInstaller;

/**
 * @internal
 */
final class Installer extends AbstractInstaller
{
    /**
     * {@inheritdoc}
     */
    protected $name = 'install:menu';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Menu: Build beautiful CLI interactive menus';

    /**
     * {@inheritdoc}
     */
    public function install(): void
    {
        $this->require('nunomaduro/laravel-console-menu "^3.4"');
    }
}
