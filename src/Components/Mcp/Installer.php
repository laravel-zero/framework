<?php

namespace LaravelZero\Framework\Components\Mcp;

use LaravelZero\Framework\Components\AbstractInstaller;

final class Installer extends AbstractInstaller
{
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
            $this->require('laravel/mcp');
        });
    }
}
