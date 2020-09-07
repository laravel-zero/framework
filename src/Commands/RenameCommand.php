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

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use function sprintf;

final class RenameCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected $signature = 'app:rename {name? : The new name}';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Set the application name';

    /**
     * {@inheritdoc}
     */
    public function handle(): void
    {
        $this->info('Renaming the application...');

        $this->rename();
    }

    /**
     * Updates the binary name and the application
     * name on the composer.json.
     */
    private function rename(): RenameCommand
    {
        $name = $this->asksForApplicationName();

        if (File::exists($this->app->basePath($name))) {
            $this->app->abort(403, 'Folder or file already exists.');
        } else {
            $this->renameBinary($name)
                ->updateComposer($name);
        }

        return $this;
    }

    /**
     * Asks for the application name.
     *
     * If there is no interaction, we take the folder basename.
     */
    private function asksForApplicationName(): string
    {
        if (empty($name = $this->input->getArgument('name'))) {
            $name = $this->ask('What is your application name?');
        }

        if (empty($name)) {
            $name = trim(basename($this->app->basePath()));
        }

        return Str::lower($name);
    }

    /**
     * Update composer json with related information.
     */
    private function updateComposer(string $name): RenameCommand
    {
        $this->task(
            'Updating config/app.php "name" property',
            function () use ($name): bool {
                $neededLine = "'name' => '".Str::ucfirst($this->getCurrentBinaryName())."'";

                if (! Str::contains($contents = $this->getConfig(), $neededLine)) {
                    return false;
                }
                File::put(
                    $this->app->configPath('app.php'),
                    Str::replaceFirst(
                        $neededLine,
                        "'name' => '".Str::ucfirst($name)."'",
                        $contents
                    )
                );

                return true;
            }
        );

        $this->task(
            'Updating composer "bin"',
            function () use ($name): bool {
                $neededLine = '"bin": ["'.$this->getCurrentBinaryName().'"]';

                if (! Str::contains($contents = $this->getComposer(), $neededLine)) {
                    return false;
                }

                File::put(
                    $this->app->basePath('composer.json'),
                    Str::replaceFirst(
                        $neededLine,
                        '"bin": ["'.$name.'"]',
                        $contents
                    )
                );

                return true;
            }
        );

        return $this;
    }

    /**
     * Renames the application binary.
     */
    private function renameBinary(string $name): RenameCommand
    {
        $this->task(
            sprintf('Renaming application to "%s"', $name),
            function () use ($name): bool {
                return File::move($this->app->basePath($this->getCurrentBinaryName()), $this->app->basePath($name));
            }
        );

        return $this;
    }

    /**
     * Returns the current binary name.
     */
    private function getCurrentBinaryName(): string
    {
        $composer = $this->getComposer();

        return current(@json_decode($composer, true, 512, JSON_THROW_ON_ERROR)->bin);
    }

    /**
     * Returns the composer.json file contents.
     */
    private function getComposer(): string
    {
        $filePath = $this->app->basePath('composer.json');

        if (! File::exists($filePath)) {
            $this->app->abort(400, 'The file composer.json not found');
        }

        return File::get($filePath);
    }

    /**
     * Returns the config file contents.
     */
    private function getConfig(): string
    {
        $filePath = $this->app->configPath('app.php');

        if (! File::exists($filePath)) {
            $this->app->abort(400, 'The file config/app.php not found');
        }

        return File::get($filePath);
    }
}
