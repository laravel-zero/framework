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

namespace LaravelZero\Framework\Commands;

use function ucfirst;
use Illuminate\Foundation\Console\ConsoleMakeCommand;

final class MakeCommand extends ConsoleMakeCommand
{
    /**
     * {@inheritdoc}
     */
    protected $description = 'Create a new command';

    /**
     * {@inheritdoc}
     */
    protected function getNameInput(): string
    {
        return ucfirst(parent::getNameInput());
    }

    /**
     * {@inheritdoc}
     */
    protected function getStub(): string
    {
        return __DIR__.DIRECTORY_SEPARATOR.'stubs'.DIRECTORY_SEPARATOR.'console.stub';
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\Commands';
    }
}
