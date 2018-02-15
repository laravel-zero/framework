<?php

namespace Tests;

use LaravelZero\Framework\Application;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;

/**
 * This is the Laravel Zero Framework test case class.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
abstract class TestCase extends BaseTestCase
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        if (! defined('ARTISAN_BINARY')) {
            define('ARTISAN_BINARY', 'application');
        }

        $this->app = $this->createApplication();
    }

    /**
     * Creates a new instance of the application.
     *
     * @return \Illuminate\Contracts\Foundation\Application
     */
    protected function createApplication(): ApplicationContract
    {
        $app = require __DIR__ . DIRECTORY_SEPARATOR . 'Application' . DIRECTORY_SEPARATOR . 'bootstrap' . DIRECTORY_SEPARATOR . 'app.php';

        Application::setInstance($app);

        $app->make(\Illuminate\Contracts\Console\Kernel::class)
            ->bootstrap();

        return $app;
    }
}
