<?php

namespace LaravelZero\Framework\Bootstrappers;

/**
 * This is the Zero Framework Bootstrapper Facade class.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
class Facades extends Bootstrapper
{
    /**
     * {@inheritdoc}
     */
    public function bootstrap(): void
    {
        \Illuminate\Support\Facades\Facade::setFacadeApplication($this->container);
    }
}
