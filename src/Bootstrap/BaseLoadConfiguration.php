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

use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Illuminate\Foundation\Bootstrap\LoadConfiguration;
use Symfony\Component\Finder\Finder;

use function basename;
use function ksort;

/**
 * @internal
 */
final class BaseLoadConfiguration extends LoadConfiguration
{
    /**
     * Get all of the configuration files for the application.
     */
    protected function getConfigurationFiles(ApplicationContract $app): array
    {
        $files = [];

        $configPath = $app->configPath();

        $configFiles = Finder::create()
            ->files()
            ->name('*.php')
            ->in($configPath);

        foreach ($configFiles as $file) {
            $directory = $this->getNestedDirectory($file, $configPath);
            $files[$directory.basename($file->getPathname(), '.php')] = $file->getPathname();
        }

        ksort($files, SORT_NATURAL);

        return $files;
    }
}
