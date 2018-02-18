<?php

/**
 * This file is part of Laravel Zero.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace LaravelZero\Framework\Providers\GitVersion;

use Symfony\Component\Process\Process;
use Illuminate\Support\ServiceProvider;

/**
 * This is the Laravel Zero Framework Git Version Provider implementation.
 */
class GitVersionServiceProvider extends ServiceProvider
{
    /**
     * Register Git Tag service.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(
            'git.version',
            function ($app) {
                ($process = new Process('git describe --tags $(git rev-list --tags --max-count=1)', $app->basePath()))->run();

                return trim($process->getOutput()) ?: 'unreleased';
            }
        );
    }
}
