<?php

namespace LaravelZero\Framework\Bootstrappers;

/**
 * This is the Zero Framework Bootstrapper Constants class.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
class Constants extends Bootstrapper
{
    /**
     * {@inheritdoc}
     */
    public function bootstrap(): void
    {
        if (! defined('ARTISAN_BINARY')) {
            define('ARTISAN_BINARY', BASE_PATH.'/'.basename($_SERVER['SCRIPT_FILENAME']));
        }
    }
}
