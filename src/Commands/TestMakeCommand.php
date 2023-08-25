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

namespace LaravelZero\Framework\Commands;

use Illuminate\Foundation\Console\TestMakeCommand as BaseTestMakeCommand;

use function ucfirst;

final class TestMakeCommand extends BaseTestMakeCommand
{
    /** {@inheritdoc} */
    protected function getNameInput(): string
    {
        return ucfirst(parent::getNameInput());
    }

    /** {@inheritdoc} */
    protected function getStub(): string
    {
        $suffix = $this->option('unit') ? '.unit.stub' : '.stub';

        $relativePath = $this->option('pest')
            ? '/stubs/pest'.$suffix
            : '/stubs/test'.$suffix;

        return file_exists($customPath = $this->laravel->basePath(trim($relativePath, '/')))
            ? $customPath
            : __DIR__.$relativePath;
    }
}
