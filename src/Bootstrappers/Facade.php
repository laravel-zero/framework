<?php

namespace LaravelZero\Framework\Bootstrappers;

use I;

/**
 * This is the Zero Framework Bootstrapper Facade class.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
class Facade extends Bootstrapper
{
    /**
     * {@inheritdoc}
     */
    public function bootstrap(): void
    {
        \Illuminate\Support\Facades\Facade::setFacadeApplication($this->container);
    }
}
