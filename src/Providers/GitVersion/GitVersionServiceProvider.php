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

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Process\Process;

/**
 * @codeCoverageIgnore
 * @internal
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
                $lastRevisionTag = '$(git rev-list --tags --max-count=1)';

                if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                    $taskGetLastRevisionTag = ['git', 'rev-list', '--tags', '--max-count=1'];

                    $process = tap(new Process($taskGetLastRevisionTag, $app->basePath()))->run();

                    $lastRevisionTag = trim($process->getOutput()) ?: 'unreleased';

                    if ($lastRevisionTag === 'unreleased') {
                        return 'unreleased';
                    }
                }
                $task = ['git', 'describe', '--tags', $lastRevisionTag];

                $process = tap(new Process($task, $app->basePath()))->run();

                return trim($process->getOutput()) ?: 'unreleased';
            }
        );
    }
}
