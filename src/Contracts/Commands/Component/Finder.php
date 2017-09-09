<?php

namespace LaravelZero\Framework\Contracts\Commands\Component;

/**
 * This is the Zero Framework component finder contract.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
interface Finder
{
    /**
     * Finds all the available components.
     *
     * @return string[]
     */
    public function find(): array;
}
