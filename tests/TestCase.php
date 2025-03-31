<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use LaravelZero\Framework\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * The application instance.
     *
     * @var ApplicationContract
     */
    protected $app;

    /** {@inheritdoc} */
    protected function setUp(): void
    {
        if (! defined('ARTISAN_BINARY')) {
            define('ARTISAN_BINARY', 'application');
        }

        parent::setUp();
    }

    public function createApplication(): ApplicationContract
    {
        $app = require __DIR__.'/Application/bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
