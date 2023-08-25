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

namespace LaravelZero\Framework\Components\Database;

use Illuminate\Database\Migrations\Migrator as BaseMigrator;
use Illuminate\Support\Str;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

use function collect;

/**
 * @codeCoverageIgnore
 *
 * @internal
 */
class Migrator extends BaseMigrator
{
    /**
     * Get all of the migration files in a given path.
     *
     * Differs from the original `getMigrationFiles` because
     * the phar don't support globs.
     */
    public function getMigrationFiles($paths): array
    {
        return collect($paths)
            ->flatMap(
                function ($path) {
                    if (Str::endsWith($path, '.php')) {
                        $finder = (new Finder)->in([dirname($path)])
                            ->files()
                            ->name(basename($path));
                    } else {
                        $finder = (new Finder)->in([$path])
                            ->files();
                    }

                    return collect($finder)
                        ->map(
                            fn (SplFileInfo $file) => $file->getPathname()
                        )
                        ->all();
                }
            )
            ->filter()
            ->sortBy(
                fn ($file) => $this->getMigrationName($file)
            )
            ->values()
            ->keyBy(
                function ($file) {
                    return $this->getMigrationName($file);
                }
            )
            ->all();
    }
}
