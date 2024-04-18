<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use LaravelZero\Framework\Application;
use LaravelZero\Framework\Testing\TestCase as BaseTestCase;

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

        parent::setUp();

        $this->app = $this->createApplication();
    }

    /**
     * Creates a new instance of the application.
     */
    public function createApplication(): ApplicationContract
    {
        $app = require __DIR__.'/Application/bootstrap/app.php';

        Application::setInstance($app);

        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }
}
