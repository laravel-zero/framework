<?php

namespace LaravelZero\Framework\Commands\Component\Illuminate\Events;

use Illuminate\Filesystem\Filesystem;
use LaravelZero\Framework\Contracts\Commands\Component\Installer as InstallerContract;
use LaravelZero\Framework\Commands\Component\Installer as BaseInstaller;

class Installer extends BaseInstaller implements InstallerContract
{
    /**
     * {@inheritdoc}
     */
    protected $name = 'install:events';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Installs the illuminate/events related generator commands';

    /**
     * {@inheritdoc}
     */
    public function getComponentName(): string
    {
        return 'events';
    }

    /**
     * {@inheritdoc}
     */
    public function install(): bool
    {
        $this->installServiceProvider();

        $this->info('Base Component installed! For usage information please reference the Laravel Framework documentation, here: https://laravel.com/docs/5.5/events');

        return true;
    }

    /**
     * Installs the application-level EventServiceProvider where users can register their
     * custom events and event listeners.
     */
    protected function installServiceProvider()
    {
        $files = $this->laravel->make(Filesystem::class);
        $stub = str_replace(
            'DummyNamespace',
            $this->laravel->getNamespace() . '\Providers',
            $files->get($this->getStub())
        );

        $files->put($this->laravel->basePath('app') . '/Providers/' . 'EventServiceProvider.php', $stub);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/event-service-provider.stub';
    }
}
