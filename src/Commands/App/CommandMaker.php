<?php

namespace LaravelZero\Framework\Commands\App;

use Illuminate\Foundation\Console\ConsoleMakeCommand;

class CommandMaker extends ConsoleMakeCommand
{
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new command';

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        return ucfirst(parent::getNameInput());
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/console.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Commands';
    }
}
