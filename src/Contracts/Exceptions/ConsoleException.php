<?php

namespace LaravelZero\Framework\Contracts\Exceptions;

use Symfony\Component\Console\Exception\ExceptionInterface;

/**
 * This is the Laravel Zero Framework error handler contract.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
interface ConsoleException extends ExceptionInterface
{
    /**
     * Returns the exit code.
     *
     * @return int
     */
    public function getExitCode(): int;

    /**
     * Returns the headers.
     *
     * @return array
     */
    public function getHeaders(): array;

    /**
     * Sets the headers.
     *
     * @param array $headers
     *
     * @return \LaravelZero\Framework\Contracts\Exceptions\ConsoleException
     */
    public function setHeaders(array $headers): ConsoleException;
}
