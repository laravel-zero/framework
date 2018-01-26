<?php

/**
 * This file is part of Laravel Zero.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace LaravelZero\Framework\Commands\Component\Illuminate\Log;

use LaravelZero\Framework\Commands\Component\AbstractInstaller;

/**
 * This is the Laravel Zero Framework Log Component Installer Implementation.
 */
class Installer extends AbstractInstaller
{
    /**
     * {@inheritdoc}
     */
    protected $name = 'install:log';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Installs illuminate/log';

    /**
     * {@inheritdoc}
     */
    public function getComponentName(): string
    {
        return 'log';
    }

    /**
     * {@inheritdoc}
     */
    public function install(): bool
    {
        $this->require('illuminate/log "5.5.*"');

        $this->info('Usage:');
        $this->comment(
            '
use Illuminate\Support\Facades\Log;

Log::emergency($message);
Log::alert($message);
Log::critical($message);
Log::error($message);
Log::warning($message);
Log::notice($message);
Log::info($message);
Log::debug($message);
'
        );

        return true;
    }
}
