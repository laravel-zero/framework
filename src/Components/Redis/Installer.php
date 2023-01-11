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

namespace LaravelZero\Framework\Components\Redis;

use LaravelZero\Framework\Components\AbstractInstaller;

/** @internal */
final class Installer extends AbstractInstaller
{
    /** {@inheritdoc} */
    protected $name = 'install:redis';

    /** {@inheritdoc} */
    protected $description = 'Redis: In-memory data structure';

    /** {@inheritdoc} */
    public function install(): void
    {
        $this->require('illuminate/redis "^10.0"');
    }
}
