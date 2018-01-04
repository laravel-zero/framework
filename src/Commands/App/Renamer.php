<?php

namespace LaravelZero\Framework\Commands\App;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use LaravelZero\Framework\Commands\Command;

/**
 * This is the Laravel Zero Framework renamer command class.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
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
     * Perform project modifications in order to apply the
     * application name on the composer and on the binary.
     *
     * @return $this
     */
    protected function rename(): Renamer
    {
        $name = $this->asksForApplicationName();

        if (File::exists(base_path($name))) {
            $this->error("Could't rename: Folder or file already exists.");
        } else {
            $this->renameBinary($name)->updateComposer($name);
        }

        return $this;
    }

    /**
     * Display an welcome message.
     *
     * @return $this
     */
    protected function displayWelcomeMessage(): Renamer
    {
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
            $name = trim(basename(BASE_PATH));
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
        $this->setComposer(
            Str::replaceFirst(
                '"bin": ["'.$this->getCurrentBinaryName().'"]', '"bin": ["'.$name.'"]', $this->getComposer()
            )
        );

        $this->output->writeln('Updating composer: <info>✔</info>');

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
        File::move(base_path($this->getCurrentBinaryName()), base_path($name));

        $this->output->writeln("Renaming application to: <info>$name</info>");

        return $this;
    }

    /**
     * Set composer file.
     *
     * @param string $composer
     *
     * @return $this
     */
    protected function setComposer(string $composer): Renamer
    {
        File::put(base_path('composer.json'), $composer);

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
        $file = base_path('composer.json');

        if (! File::exists($file)) {
            $this->error('composer.json not found.');
            exit(0);
        }

        return File::get($file);
    }
}
