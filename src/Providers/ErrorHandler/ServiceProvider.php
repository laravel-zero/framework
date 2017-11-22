<?php

namespace LaravelZero\Framework\Providers\ErrorHandler;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use LaravelZero\Framework\Contracts\Application as ApplicationContract;
use LaravelZero\Framework\Contracts\Providers\ErrorHandler as ErrorHandlerContract;

/**
 * This is the Laravel Zero Framework error handler service provider class.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(ErrorHandlerContract $errorHandler): void
    {
        if ($this->app->environment() === 'production') {
            $this->app->make(ApplicationContract::class)
                ->setCatchExceptions(true);
        } else {
            $errorHandler->register();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function register(): void
    {
        $this->app->singleton(
            ErrorHandlerContract::class,
            function () {
                return new ErrorHandler;
            }
        );
    }
}
