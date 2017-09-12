<?php

namespace LaravelZero\Framework\Contracts;

use Symfony\Component\Console\Command\Command;
use Illuminate\Contracts\Console\Application as ApplicationContract;

/**
 * This is the Zero Framework application contract.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
interface Application extends ApplicationContract
{
    /**
     * Sets the application name.
     *
     * @param $name
     *
     * @return void
     */
    public function setName($name);

    /**
     * Sets the application version.
     *
     * @param $version
     *
     * @return void
     */
    public function setVersion($version);

    /**
     * Returns the application's container.
     *
     * @return \Illuminate\Contracts\Container\Container
     */
    public function getContainer();

    /**
     * @param \Symfony\Component\Console\Command\Command $command
     *
     * @return void
     */
    public function add(Command $command);
}
