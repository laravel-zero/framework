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

namespace LaravelZero\Framework\Bootstrap;

use Dotenv\Dotenv;
use LaravelZero\Framework\Application;
use LaravelZero\Framework\Contracts\BoostrapperContract;
use LaravelZero\Framework\Providers\Build\Build;

/**
 * @internal
 */
final class BuildLoadEnvironmentVariables implements BoostrapperContract
{
    /**
     * @var \LaravelZero\Framework\Providers\Build\Build
     */
    private $build;

    /**
     * BuildLoadEnvironmentVariables constructor.
     */
    public function __construct(Build $build)
    {
        $this->build = $build;
    }

    /**
     * {@inheritdoc}
     */
    public function bootstrap(Application $app): void
    {
        /*
         * Override environment variables with the environment file along side the Phar file.
         */
        if ($this->build->shouldUseEnvironmentFile()) {
            Dotenv::createMutable($this->build->getDirectoryPath(), $this->build->environmentFile())->load();
        }
    }
}
