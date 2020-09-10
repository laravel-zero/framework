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

namespace LaravelZero\Framework\Components\Pest;

use LaravelZero\Framework\Components\AbstractInstaller;

/** @internal */
final class Installer extends AbstractInstaller
{
    /** {@inheritdoc} */
    protected $name = 'install:pest';

    /** {@inheritdoc} */
    protected $description = 'Pest: Use the Pest testing framework for your test suite';

    /** {@inheritdoc} */
    public function install(): void
    {
        $this->remove('phpunit/phpunit');
        $this->require('pestphp/pest');
    }
}
