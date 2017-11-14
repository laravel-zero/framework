<?php

namespace LaravelZero\Framework\Bootstrappers;

use Iterator;
use Symfony\Component\Finder\Finder;

/**
 * This is the Laravel Zero Framework Bootstrapper Configuration class.
 *
 * Configures the console application.
 *
 * Takes in consideration the app name and the app version. Also
 * adds all the application commands.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
class Configurations extends Bootstrapper
{
    /**
     * {@inheritdoc}
     */
    public function bootstrap(): void
    {
        $config = $this->container->make('config');

        foreach ($this->getDetectedConfigs() as $configFile) {
            $configFilename = pathinfo($configFile)['filename'];

            $config->set($configFilename, require $configFile);
        }

        if ($name = $config->get('app.name')) {
            $this->application->setName($name);
        }

        if ($version = $config->get('app.version')) {
            $this->application->setVersion($version);
        }
    }

    /**
     * Returns detected configs.
     *
     * @return \Iterator
     */
    protected function getDetectedConfigs(): Iterator
    {
        return (new Finder)->in(config_path())
            ->files()
            ->getIterator();
    }
}
