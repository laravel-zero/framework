<?php

declare(strict_types=1);

/**
 * This file is part of Laravel Zero.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace LaravelZero\Framework\Bootstrap;

use Dotenv\Dotenv;
use function class_exists;
use LaravelZero\Framework\Application;
use Dotenv\Exception\InvalidFileException;
use Dotenv\Exception\InvalidPathException;
use LaravelZero\Framework\Contracts\BoostrapperContract;
use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables as BaseLoadEnvironmentVariables;

/**
 * @internal
 */
final class LoadEnvironmentVariables implements BoostrapperContract
{
    /**
     * {@inheritdoc}
     */
    public function bootstrap(Application $app): void
    {
        if (class_exists(Dotenv::class)) {
            $app->make(BaseLoadEnvironmentVariables::class)->bootstrap($app);

            $this->overrideProductionEnvironmentVariables($app);
        }
    }

    /**
     * Override environment variables with the environment file along side the Phar file.
     *
     * @param Application $app
     */
    private function overrideProductionEnvironmentVariables(Application $app): void
    {
        if ($app['phar.built']->isPharBuilt() && file_exists($app['phar.built']->dotEnvWithPharBuilt())) {
            try {
                (
                    new Dotenv(
                        $app['phar.built']->pharDirPath(),
                        $app['phar.built']->getDotEnvFilename()
                    )
                )->overload();
            } catch (InvalidPathException $e) {
                echo 'The path is invalid: '.$e->getMessage();
                die(1);
            } catch (InvalidFileException $e) {
                echo 'The environment file is invalid: '.$e->getMessage();
                die(1);
            }
        }
    }
}
