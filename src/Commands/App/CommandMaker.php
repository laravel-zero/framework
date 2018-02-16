<?php

/**
 * This file is part of Laravel Zero.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace LaravelZero\Framework\Commands\App;

use Illuminate\Foundation\Console\ConsoleMakeCommand;

/**
 * This is the Laravel Zero Framework Maker Command implementation.
 */
class CommandMaker extends ConsoleMakeCommand
{
    /**
     * {@inheritdoc}
     */
    protected $description = 'Create a new command';

    /**
     * {@inheritdoc}
     */
    protected function getNameInput()
    {
        return ucfirst(parent::getNameInput());
    }

    /**
     * {@inheritdoc}
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/console.stub';
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Commands';
    }
}
