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
 *
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
                $process = Process::fromShellCommandline(
                    'git describe --tags --abbrev=0',
                    $app->basePath()
                );

                $process->run();

                return trim($process->getOutput()) ?: 'unreleased';
            }
        );
    }
}
