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

namespace LaravelZero\Framework\Components\ConsoleDusk;

use LaravelZero\Framework\Components\AbstractInstaller;

/**
 * @internal
 */
final class Installer extends AbstractInstaller
{
    /**
     * {@inheritdoc}
     */
    protected $name = 'install:console-dusk';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Console Dusk: Browser automation';

    /**
     * {@inheritdoc}
     */
    public function install(): void
    {
        $this->require('nunomaduro/laravel-console-dusk');

        $this->info('Usage:');
        $this->comment(
            '
class VisitLaravelZeroCommand extends Command
{
    public function handle()
    {
        $this->browse(function ($browser) {
            $browser->visit("https://laravel-zero.com")
                ->assertSee("100% Open Source");
        });
    }
}
'
        );
    }
}
