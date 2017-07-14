<?php

/**
 * This file is part of Zero Framework.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Tests;

use NunoMaduro\ZeroFramework\Application;
use PHPUnit\Framework\TestCase as BaseTestCase;

/**
 * The is the Zero Framework test case class.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
abstract class TestCase extends BaseTestCase
{
    /**
     * The application instance.
     *
     * @var \NunoMaduro\ZeroFramework\Application
     */
    protected $app;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        if (! defined('BASE_PATH')) {
            define('BASE_PATH', __DIR__);
        }

        $this->app = $this->createApplication();
    }

    /**
     * Creates a new instance of the application.
     *
     * @return \NunoMaduro\ZeroFramework\Application
     */
    protected function createApplication(): Application
    {
        return new Application;
    }
}
