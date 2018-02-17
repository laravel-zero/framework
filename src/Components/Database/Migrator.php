<?php

/**
 * This file is part of Laravel Zero.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace LaravelZero\Framework\Components\Database;

use Symfony\Component\Finder\Finder;
use Illuminate\Database\Migrations\Migrator as BaseMigrator;

/**
 * This is the Laravel Zero Framework Database Component Migrator Implementation.
 */
class Migrator extends BaseMigrator
{
    /**
     * Get all of the migration files in a given path.
     *
     * Differs from the original `getMigrationFiles` because
     * the build feature don't support globs.
     *
     * @param  string|array  $paths
     *
     * @return array
     */
    public function getMigrationFiles($paths)
    {
        return collect($paths)->flatMap(function ($path) {
            return collect((new Finder)->in([$path])->files())->map( function ($file) {
                return $file->getPathname();
            })->all();
        })->filter()->sortBy(function ($file) {
            return $this->getMigrationName($file);
        })->values()->keyBy(function ($file) {
            return $this->getMigrationName($file);
        })->all();
    }
}
