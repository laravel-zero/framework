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

namespace LaravelZero\Framework\Components\Log;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Log\ContextLogProcessor as ContextLogProcessorContract;
use Illuminate\Log\Context\ContextLogProcessor;
use Illuminate\Log\LogServiceProvider;
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
        return file_exists($this->app->configPath('logging.php'))
            && $this->app['config']->get('logging.useDefaultProvider', true) === true;
    }

    /**
     * {@inheritdoc}
     */
    public function register(): void
    {
        $this->app->register(LogServiceProvider::class);
        if (class_exists(ContextLogProcessor::class)) {
            $this->app->bind(ContextLogProcessorContract::class, fn () => new ContextLogProcessor);
        }

        /** @var Repository $config */
        $config = $this->app['config'];

        $config->set('logging.default', $config->get('logging.default') ?: 'default');
    }
}
