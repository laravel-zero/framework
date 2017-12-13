<?php

namespace Tests;

use LaravelZero\Framework\Application;
use PHPUnit\Framework\TestCase as BaseTestCase;

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
     * @var \LaravelZero\Framework\Application
     */
    protected $app;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        if (! defined('BASE_PATH')) {
            define('BASE_PATH', __DIR__.DIRECTORY_SEPARATOR.'Application');
        }

        $this->app = $this->createApplication();
    }

    /**
     * Creates a new instance of the application.
     *
     * @return \LaravelZero\Framework\Application
     */
    protected function createApplication(): Application
    {
        return new Application;
    }
}
