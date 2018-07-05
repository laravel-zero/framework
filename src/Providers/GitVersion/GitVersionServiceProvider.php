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

namespace LaravelZero\Framework\Providers\GitVersion;

use Symfony\Component\Process\Process;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;

/**
 * @codeCoverageIgnore
 */
final class GitVersionServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function register(): void
    {
        $this->app->bind(
            'git.version',
            function (Application $app) {
                $task = 'git describe --tags $(git rev-list --tags --max-count=1)';

                $process = tap(new Process($task, $app->basePath()))->run();

                return trim($process->getOutput()) ?: 'unreleased';
            }
        );
    }
}
