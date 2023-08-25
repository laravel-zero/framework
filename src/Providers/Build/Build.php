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

namespace LaravelZero\Framework\Providers\Build;

use Phar;

use function dirname;

/**
 * @internal
 */
class Build
{
    /**
     * The name of the environment file.
     *
     * @var string
     */
    private $environmentFile = '.env';

    /**
     * Checks if the application is running from a Phar file.
     */
    public function isRunning(): bool
    {
        return Phar::running() !== '';
    }

    /**
     * Returns the directory path from where the Phar is running.
     */
    public function getDirectoryPath(): string
    {
        return dirname($this->getPath());
    }

    /**
     * Returns the path from where the Phar is running.
     */
    public function getPath(): string
    {
        return Phar::running(false);
    }

    /**
     * Returns the .env path with Phar running.
     */
    public function environmentFilePath(): string
    {
        return $this->getDirectoryPath().DIRECTORY_SEPARATOR.$this->environmentFile;
    }

    /**
     * Checks if the build is running and there's an available environment file.
     */
    public function shouldUseEnvironmentFile(): bool
    {
        return $this->isRunning() && file_exists($this->environmentFilePath());
    }

    /**
     * The file for environment file.
     */
    public function environmentFile(): string
    {
        return $this->environmentFile;
    }
}
