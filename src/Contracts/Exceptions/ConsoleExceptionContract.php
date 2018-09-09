<?php

declare(strict_types=1);

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
 * @internal
 */
interface ConsoleExceptionContract extends ExceptionInterface
{
    /**
     * Gets the exit code.
     */
    public function getExitCode(): int;

    /**
     * Gets the headers.
     */
    public function getHeaders(): array;

    /**
     * Sets the headers.
     */
    public function setHeaders(array $headers): ConsoleExceptionContract;
}
