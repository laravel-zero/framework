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

namespace LaravelZero\Framework\Components\Mcp;

use Illuminate\Contracts\Config\Repository;
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
        return class_exists(\Laravel\Mcp\Server\McpServiceProvider::class);
    }

    /**
     * {@inheritdoc}
     */
    public function boot(): void
    {
        if ($this->app->environment() !== 'production') {
            $this->commands(
                [
                    \Laravel\Mcp\Console\Commands\InspectorCommand::class,
                ]
            );
        }

        $this->commands(
            [
                \Laravel\Mcp\Console\Commands\MakePromptCommand::class,
                \Laravel\Mcp\Console\Commands\MakeResourceCommand::class,
                \Laravel\Mcp\Console\Commands\MakeServerCommand::class,
                \Laravel\Mcp\Console\Commands\MakeToolCommand::class,
                \Laravel\Mcp\Console\Commands\StartCommand::class
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function register(): void
    {
        $this->app->register(\Laravel\Mcp\Server\McpServiceProvider::class);
    }
}
