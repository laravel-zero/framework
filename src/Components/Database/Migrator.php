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

use function collect;
use Illuminate\Database\Migrations\Migrator as BaseMigrator;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

/**
 * @codeCoverageIgnore
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
                static function (string $path): array {
                    return collect(
                        (new Finder)->in([$path])
                            ->files()
                    )
                        ->map(
                            static function (SplFileInfo $file): string {
                                return $file->getPathname();
                            }
                        )
                        ->all();
                }
            )
            ->filter()
            ->sortBy(
                function (string $file): string {
                    return $this->getMigrationName($file);
                }
            )
            ->values()
            ->keyBy(
                function (string $file): string {
                    return $this->getMigrationName($file);
                }
            )
            ->all();
    }
}
