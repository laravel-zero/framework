<?php

namespace LaravelZero\Framework\Providers\ErrorHandler;

use NunoMaduro\Collision\Provider;
use Symfony\Component\Console\Output\OutputInterface;
use NunoMaduro\Collision\Contracts\Provider as ProviderContract;
use LaravelZero\Framework\Contracts\Providers\ErrorHandler as ErrorHandlerContract;

/**
 * This is the Laravel Zero Framework error handler class.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
class ErrorHandler implements ErrorHandlerContract
{
    /**
     * @var \NunoMaduro\Collision\Contracts\Provider
     */
    protected $provider;

    /**
     * Creates a new instance of the class.
     *
     * @param \NunoMaduro\Collision\Contracts\Provider|null $provider
     */
    public function __construct(ProviderContract $provider = null)
    {
        $this->provider = $provider ?: new Provider;
    }

    /**
     * {@inheritdoc}
     */
    public function register(): void
    {
        $this->provider->register();
    }

    /**
     * {@inheritdoc}
     */
    public function setOutput(OutputInterface $output): void
    {
        $this->provider->getHandler()
            ->setOutput($output);
    }
}
