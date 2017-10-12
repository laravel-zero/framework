<?php

namespace LaravelZero\Framework\Contracts\Providers;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * This is the Zero Framework ErrorHandler Contract.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
interface ErrorHandler
{
    /**
     * Registers the error handler.
     */
    public function register(): void;

    /**
     * Sets the output of the error handler.
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    public function setOutput(OutputInterface $output): void;
}
