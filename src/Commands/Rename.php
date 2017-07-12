<?php

/**
 * This file is part of Zero Framework.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace NunoMaduro\ZeroFramework\Commands;

use Illuminate\Support\Str;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;

/**
 * The is the Zero Framework rename command class.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
class Rename extends AbstractCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rename your application';

    /**
     * Holds an instance of SymfonyStyle.
     *
     * @var \Symfony\Component\Console\Style\SymfonyStyle;
     */
    protected $style;

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->style = new SymfonyStyle($this->input, $this->output);

        $this->displayWelcomeMessage()
            ->install();
    }

    /**
     * Configure the command options.
     *
     * Ask for the name of the build.
     */
    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::OPTIONAL);
    }

    /**
     * Perform project modifications in order to apply the
     * application name on the composer and on the binary.
     *
     * @return $this
     */
    protected function install(): Rename
    {
        $name = $this->asksForApplicationName();

        return $this->rename($name)
            ->updateComposer($name);
    }

    /**
     * Display an welcome message.
     *
     * @return $this
     */
    protected function displayWelcomeMessage(): Rename
    {
        $this->style->title('Crafting application...');

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
    protected function updateComposer(string $name): Rename
    {
        $this->setComposer(
            Str::replaceFirst(
                '"bin": ["'.$this->getCurrentBinaryName().'"]',
                '"bin": ["'.$name.'"]',
                $this->getComposer()
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
    protected function rename(string $name): Rename
    {
        rename(BASE_PATH.'/'.$this->getCurrentBinaryName(), BASE_PATH.'/'.$name);

        $this->output->writeln('Renaming application: <info>✔</info>');

        return $this;
    }

    /**
     * Set composer file.
     *
     * @param string $composer
     *
     * @return $this
     */
    protected function setComposer(string $composer): Rename
    {
        file_put_contents(BASE_PATH.'/composer.json', $composer);

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
        $file = BASE_PATH.'/composer.json';

        if (! file_exists($file)) {
            $this->error("You can't perform a install.");
            exit(0);
        }

        return file_get_contents($file);
    }
}
