<?php

namespace LaravelZero\Framework\Commands\Component;

use LaravelZero\Framework\Commands\Command;
use LaravelZero\Framework\Contracts\Commands\Component\Installer as InstallerContract;

/**
 * This is the Laravel Zero Framework component installer command class.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
abstract class Installer extends Command implements InstallerContract
{
    /**
     * {@inheritdoc}
     */
    public function handle(): void
    {
        $component = $this->getComponentName();

        $this->alert("Installing $component component...");

        $this->install();

        $this->output->writeln("The component $component installed: <info>✔</info>");
    }
}
