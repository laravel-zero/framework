<?php

/**
 * This file is part of Laravel Zero.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace LaravelZero\Framework\Components\Events;

use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Components\AbstractInstaller;

/**
 * This is the Laravel Zero Framework Events Component Installer Implementation.
 */
class Installer extends AbstractInstaller
{
    /**
     * {@inheritdoc}
     */
    protected $name = 'install:events';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Installs illuminate/events related generator commands';

    /**
     * The app's event service provider stub path.
     */
    protected const SERVICE_PROVIDER_STUB = __DIR__.DIRECTORY_SEPARATOR.'stubs'.DIRECTORY_SEPARATOR.'event-service-provider.stub';

    /**
     * {@inheritdoc}
     */
    public function install(): void
    {
        $this->task('Installing Application Event Service Provider', function () {
            if (! File::exists($this->laravel->basePath('app').'/Providers/EventServiceProvider.php')) {
                $stub = str_replace('DummyNamespace', $this->laravel->getNamespace().'Providers', File::get(static::SERVICE_PROVIDER_STUB));
                File::put($this->laravel->basePath('app').'/Providers/EventServiceProvider.php', $stub);

                return true;
            }

            return false;
        });

        $this->info('Usage:');
        $this->comment(
            '
$ php <your-application-name> event:generate
$ php <your-application-name> make:event UserCreated
$ php <your-application-name> make:listener UpdateContact
        '
        );
    }
}
