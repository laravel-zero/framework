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

namespace LaravelZero\Framework\Components\Logo;

use Illuminate\Console\Application as Artisan;
use LaravelZero\Framework\Components\AbstractComponentProvider;
use function class_exists;

/**
 * @internal
 */
final class Provider extends AbstractComponentProvider
{
    /**
     * {@inheritdoc}
     */
    public function isAvailable(): bool
    {
        return class_exists(\Zend\Text\Figlet\Figlet::class);
    }

    /**
     * {@inheritdoc}
     */
    public function register(): void
    {
        $config = $this->app['config'];

        if($config->get('logo.enabled', false)) {
            Artisan::starting(
                function ($artisan) use ($config) {
                    $artisan->setName(
                        new FigletString($config->get('app.name'), $config)
                    );
                }
            );
        }
    }
}
