<?php

namespace LaravelZero\Framework\Components\Mcp;

use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Components\AbstractInstaller;

final class Installer extends AbstractInstaller
{
    private const ROUTE_FILE = __DIR__.DIRECTORY_SEPARATOR.'stubs'.DIRECTORY_SEPARATOR.'ai.php';

    /**
     * {@inheritdoc}
     */
    protected $name = 'install:mcp';

    /**
     * {@inheritdoc}
     */
    protected $description = 'MCP: Integrate AI capabilities into your CLI applications';

    /**
     * {@inheritdoc}
     */
    public function install(): void
    {
        $this->task('Installing MCP package', function () {
            return $this->require('laravel/mcp');
        });

        $this->task('Publishing MCP route file', function () {
            return File::copy(
                self::ROUTE_FILE,
                $this->app->basePath('bootstrap/ai.php')
            );
        });
    }
}
