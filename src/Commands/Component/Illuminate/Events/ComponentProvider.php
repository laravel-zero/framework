<?php

namespace LaravelZero\Framework\Commands\Component\Illuminate\Events;

use LaravelZero\Framework\Commands\Component\AbstractComponentProvider;

/**
 * This is the Laravel Zero Framework illuminate/events component provider class.
 *
 */
class ComponentProvider extends AbstractComponentProvider
{

    protected $commands = [
        'EventGenerate' => 'command.event.generate',
        'EventMake' => 'command.event.make',
        'ListenerMake' => 'command.listener.make',
    ];

    /**
     * {@inheritdoc}
     */
    public function isAvailable(): bool
    {
        return class_exists(\Illuminate\Events\EventServiceProvider::class);
    }

    /**
     * {@inheritdoc}
     */
    public function register(): void
    {
        // Since the LaravelZero Framework requires illuminate\events be installed, the call to isAvailable() above
        // will always return true. We cannot put this particular conditional in the isAbailable method since
        // the application isn't fully booted, and we cannot resolve $this->app->getNamespace(). Only when
        // the application-level EventServiceProvider is present do we register the Event commands.
        $providerName = $this->app->getNamespace() . 'Providers\EventServiceProvider';
        if (class_exists($providerName)) {
            $this->registerServiceProvider($providerName);
            $this->registerCommands($this->commands);
        }
    }

    /**
    * Register the given commands.
    *
    * @param  array  $commands
    * @return void
    */
    protected function registerCommands(array $commands)
    {
        foreach (array_keys($commands) as $command) {
            call_user_func_array([$this, "register{$command}Command"], []);
        }

        $this->commands(array_values($commands));
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerEventGenerateCommand()
    {
        $this->app->singleton('command.event.generate', function () {
            return new EventGenerateCommand;
        });
    }
    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerEventMakeCommand()
    {
        $this->app->singleton('command.event.make', function ($app) {
            return new EventMakeCommand($app['files']);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerListenerMakeCommand()
    {
        $this->app->singleton('command.listener.make', function ($app) {
            return new ListenerMakeCommand($app['files']);
        });
    }
}
