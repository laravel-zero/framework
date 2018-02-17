<?php

/**
 * This file is part of Laravel Zero.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace LaravelZero\Framework\Commands\App;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Command;

/**
 * This is the Laravel Zero Framework Renamer Command implementation.
 */
class Renamer extends Command
{
    /**
     * {@inheritdoc}
     */
    protected $signature = 'app:rename {name? : The new name}';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Perform an application rename';

    /**
     * {@inheritdoc}
     */
    public function handle(): void
    {
        $this->alert('Renaming the application...');

        $this->rename();
    }

    /**
     * Updates the binary name and the application
     * name on the composer.json.
     *
     * @return $this
     */
    protected function rename(): Renamer
    {
        $name = $this->asksForApplicationName();

        if (File::exists(base_path($name))) {
            $this->app->abort(403, 'Folder or file already exists.');
        } else {
            $this->renameBinary($name)->updateComposer($name);
        }

        return $this;
    }

    /**
     * Asks for the application name.
     *
     * If there is no interaction, we take the folder basename.
     *
     * @return string
     */
    protected function asksForApplicationName(): string
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
     *
     * @param string $name
     *
     * @return $this
     */
    protected function updateComposer(string $name): Renamer
    {
        $this->task(
            'Updating config/app.php',
            function () use ($name) {
                return File::put(
                    config_path('app.php'),
                    Str::replaceFirst(
                        "'name' => '".Str::ucfirst($this->getCurrentBinaryName())."'",
                        "'name' => '".Str::ucfirst($name)."'",
                        $this->getConfig()
                    )
                );
            }
        );

        $this->task(
            'Updating composer',
            function () use ($name) {
                return File::put(
                    base_path('composer.json'),
                    Str::replaceFirst(
                        '"bin": ["'.$this->getCurrentBinaryName().'"]',
                        '"bin": ["'.$name.'"]',
                        $this->getComposer()
                    )
                );
            }
        );

        return $this;
    }

    /**
     * Renames the application binary.
     *
     * @param string $name
     *
     * @return $this
     */
    protected function renameBinary(string $name): Renamer
    {
        $this->task(
            sprintf('Renaming application to "%s"', $name),
            function () use ($name) {
                return File::move($this->app->basePath($this->getCurrentBinaryName()), $this->app->basePath($name));
            }
        );

        return $this;
    }

    /**
     * Returns the current binary name.
     *
     * @return string
     */
    protected function getCurrentBinaryName(): string
    {
        $composer = $this->getComposer();

        return current(@json_decode($composer)->bin);
    }

    /**
     * Get composer file.
     *
     * @return string
     */
    protected function getComposer(): string
    {
        $file = $this->app->basePath('composer.json');

        if (! File::exists($file)) {
            abort(400, 'The file composer.json not found');
        }

        return File::get($file);
    }

    /**
     * Get config file.
     *
     * @return string
     */
    protected function getConfig(): string
    {
        $file = config_path('app.php');

        if (! File::exists($file)) {
            abort(400, 'The file config/app.php not found');
        }

        return File::get($file);
    }
}
