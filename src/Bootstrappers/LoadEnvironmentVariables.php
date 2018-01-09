<?php

namespace LaravelZero\Framework\Bootstrappers;

/**
 * This is the Laravel Zero Framework Bootstrapper Load Environment Variables class.
 *
 * Loads environment variables.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
class LoadEnvironmentVariables extends Bootstrapper
{
    /**
     * {@inheritdoc}
     */
    public function bootstrap(): void
    {
        if (class_exists(\Dotenv\Dotenv::class)) {
            $this->container->make(\Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables::class)->bootstrap(
                $this->container
            );
        }
    }
}
