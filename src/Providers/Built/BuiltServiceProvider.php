<?php


/**
 * This file is part of Laravel Zero.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace LaravelZero\Framework\Providers\Built;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class BuiltServiceProvider extends ServiceProvider
{

    const CONFIG_KEY  = 'app-built';

    /**
     * {@inheritdoc}
     */
    public function boot(): void
    {
        if( $this->canReplace('filesystems') ){
            $this->replaceFileSystems();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function register(): void
    {
        $config = $this->app->make('config');

        if ($config->get( self::CONFIG_KEY ) === null) {
            $config->set( self::CONFIG_KEY , $this->getDefaultConfig());
        }
    }

    /**
     * Replace keys inside the filesystems config
     */
    protected function replaceFileSystems(): void
    {
        $currentFileSystems = config('filesystems');

        if( isset( $currentFileSystems['disks'] ) && count( $currentFileSystems['disks'] ) > 0 ){

            if( $this->canReplace('filesystems.drivers') ){

                foreach( $this->get('filesystems.drivers') as $driver => $replace ){

                    foreach( $replace as $key => $replacements ){

                        $old = array_shift( $replacements );
                        $new = array_shift( $replacements );

                        if( !empty( $old ) && !empty( $new ) ){
                            foreach( $currentFileSystems['disks'] as $diskName => $diskConfig ){
                                if( $diskConfig['driver'] === $driver && Str::startsWith( $diskConfig[ $key ], $old ) ){
                                    $diskConfig[ $key ] = str_replace( $old, $new, $diskConfig[ $key ] );
                                    Config::set('filesystems.disks.' . $diskName, $diskConfig );
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Verify if we can replace settings when app is in production
     *
     * @param null $what
     * @return bool
     */
    protected function canReplace( $what=null ): bool
    {
        $canReplace = true;
        if( !is_null( $what ) ){
            if( is_null( config( self::CONFIG_KEY . '.' . $what ) ) ){
                $canReplace = false;
            }
        }

        return config('app.production') === true && $canReplace;
    }

    /**
     * @param null $what
     *
     * @return \Illuminate\Config\Repository|mixed|null
     */
    protected function get( $what=null )
    {
        if( is_null( $what ) ){
            return config( self::CONFIG_KEY );
        }

        return config( self::CONFIG_KEY . '.' . $what );
    }


    /**
     * Returns the default application build config.
     */
    protected function getDefaultConfig(): array
    {
        return [
            'filesystems' => [
                'drivers' => [
                    'local' => [
                        'root' => [
                            $this->app->storagePath(),
                            rtrim( getcwd(), DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR . 'storage',
                        ],
                    ],
                ],
            ],
        ];
    }
}
