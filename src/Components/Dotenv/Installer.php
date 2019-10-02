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
            function () {
                if (! File::exists(base_path('.env'))) {
                    return File::put(base_path('.env'), 'CONSUMER_KEY=');
                }

                return false;
            }
        );

        $this->task(
            'Creating .env.example',
            function () {
                if (! File::exists(base_path('.env.example'))) {
                    return File::put(base_path('.env.example'), 'CONSUMER_KEY=');
                }

                return false;
            }
        );

        $this->task(
            'Updating .gitignore',
            function () {
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
