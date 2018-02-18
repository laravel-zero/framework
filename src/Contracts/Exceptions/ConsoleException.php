<?php

/**
 * This file is part of Laravel Zero.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace LaravelZero\Framework\Contracts\Exceptions;

use Symfony\Component\Console\Exception\ExceptionInterface;

/**
 * This is the Laravel Zero Framework Console Exception Contract.
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
