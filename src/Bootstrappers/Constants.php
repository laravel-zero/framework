<?php

namespace LaravelZero\Framework\Bootstrappers;

use Phar;

/**
 * This is the Laravel Zero Framework Bootstrapper Constants class.
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
        if (!empty($pharPath = Phar::running(false))) {
            $basePath = dirname($pharPath);
        } else {
            $basePath = defined('BASE_PATH') ? BASE_PATH : '';
        }

        if (! defined('ARTISAN_BINARY')) {
            define('ARTISAN_BINARY', $basePath.'/'.basename($_SERVER['SCRIPT_FILENAME']));
        }
    }
}
