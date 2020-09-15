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

namespace LaravelZero\Framework\Components\Pest;

use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Components\AbstractInstaller;

/** @internal */
final class Installer extends AbstractInstaller
{
    /** {@inheritdoc} */
    protected $name = 'install:pest';

    /** {@inheritdoc} */
    protected $description = 'Pest: Use the Pest testing framework for your test suite';

    /** {@inheritdoc} */
    public function install(): void
    {
        $this->remove('phpunit/phpunit', true);
        $this->require('pestphp/pest', true);

        if ($this->confirm('Would you like to replace the default tests with Pest', false)) {
            File::copy(__DIR__.'/stubs/Pest.php', $this->app->basePath('tests/Pest.php'));
            File::copy(__DIR__.'/stubs/Feature/InspiringCommandTest.php', $this->app->basePath('tests/Feature/InspiringCommandTest.php'));
            File::copy(__DIR__.'/stubs/Unit/ExampleTest.php', $this->app->basePath('tests/Unit/ExampleTest.php'));

            $this->info('Copied stubs to "tests/Feature/InspiringCommandTest.php" and "tests/Unit/ExampleTest.php"!');
        }
    }
}
