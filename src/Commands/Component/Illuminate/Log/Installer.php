<?php

namespace LaravelZero\Framework\Commands\Component\Illuminate\Log;

use LaravelZero\Framework\Commands\Component\Installer as BaseInstaller;
use LaravelZero\Framework\Contracts\Commands\Component\Installer as InstallerContract;

/**
 * This is the Laravel Zero Framework illuminate/database install class.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
class Installer extends BaseInstaller implements InstallerContract
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
