<?php

namespace LaravelZero\Framework\Providers\ErrorHandler;

use Throwable;
use NunoMaduro\Collision\Provider;
use NunoMaduro\Collision\Adapters\Laravel\Inspector;
use Symfony\Component\Console\Exception\ExceptionInterface;
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
            $this->app->make(ApplicationContract::class)->setCatchExceptions(true);
        } else {
            $errorHandler->register();
            set_exception_handler(
                function (Throwable $e) use ($errorHandler) {
                    $handler = $this->app->make(ErrorHandlerContract::class)->getProvider()->getHandler();
                    if ($e instanceof ExceptionInterface) {
                        $this->app->make(ApplicationContract::class)->renderException(
                            $e, $handler->getWriter()->getOutput()
                        );
                    } else {
                        $handler->setInspector(new Inspector($e));
                        $handler->handle();
                    }
                }
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function register(): void
    {
        $this->app->singleton(
            ErrorHandlerContract::class, function () {
                return new ErrorHandler;
            }
        );
    }
}
