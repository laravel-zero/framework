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

namespace LaravelZero\Framework\Components\Http;

use LaravelZero\Framework\Components\AbstractInstaller;

/**
 * @internal
 */
final class Installer extends AbstractInstaller
{
    /**
     * {@inheritdoc}
     */
    protected $name = 'install:http';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Http: Manage web requests using a fluent HTTP client';

    /**
     * {@inheritdoc}
     */
    public function install(): void
    {
        $this->require('guzzlehttp/guzzle "^7.5"');
        $this->require('illuminate/http "^10.0"');
    }
}
