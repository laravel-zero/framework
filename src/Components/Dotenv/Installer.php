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

namespace LaravelZero\Framework\Components\Dotenv;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use LaravelZero\Framework\Components\AbstractInstaller;

/**
 * @internal
 */
final class Installer extends AbstractInstaller
{
    /**
     * {@inheritdoc}
     */
    protected $name = 'install:dotenv';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Dotenv: Loads environment variables from ".env"';

    /**
     * {@inheritdoc}
     */
    public function install(): void
    {
        $this->task(
            'Creating .env',
            static function (): bool {
                if (! File::exists(base_path('.env'))) {
                    if (! File::put(base_path('.env'), 'CONSUMER_KEY=')) {
                        return false;
                    }

                    return true;
                }

                return false;
            }
        );

        $this->task(
            'Creating .env.example',
            static function (): bool {
                if (! File::exists(base_path('.env.example'))) {
                    if (! File::put(base_path('.env.example'), 'CONSUMER_KEY=')) {
                        return false;
                    }

                    return true;
                }

                return false;
            }
        );

        $this->task(
            'Updating .gitignore',
            static function (): bool {
                $gitignorePath = base_path('.gitignore');
                if (File::exists($gitignorePath)) {
                    $contents = File::get($gitignorePath);
                    $neededLine = '.env';
                    if (! Str::contains($contents, $neededLine)) {
                        File::append($gitignorePath, $neededLine.PHP_EOL);

                        return true;
                    }
                }

                return false;
            }
        );
    }
}
