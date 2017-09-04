<?php

/**
 * This file is part of Zero Framework.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace LaravelZero\Framework\Commands\Component;

use Symfony\Component\Console\Input\InputArgument;
use LaravelZero\Framework\Commands\AbstractCommand;
use LaravelZero\Framework\Contracts\Providers\Composer as ComposerContract;

/**
 * This is the Zero Framework component installer command class.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
class Installer extends AbstractCommand
{
    /**
     * The name of the console command.
     *
     * @var string
     */
    protected $name = 'component:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install component';

    /**
     * {@inheritdoc}
     */
    public function handle(): void
    {
        $this->alert('Installing a new component...');

        $this->install();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::OPTIONAL);
    }

    /**
     * Installs the desired component on the application.
     *
     * @return $this
     */
    protected function install(): Installer
    {
        if (! $component = $this->argument('name')) {
            $component = $this->choice(
                'Please choose the component',
                $this->getContainer()
                    ->make(Finder::class)
                    ->find()
            );
        }

        $installerClass = __NAMESPACE__.'\\'.str_replace('/', '\\', ucwords($component, ' / ')).'\Installer';

        $composer = $this->getContainer()
            ->make(ComposerContract::class);

        if ((new $installerClass)->install($this, $composer)) {
            $this->output->writeln("The component $component installation: <info>✔</info>");
        }

        return $this;
    }
}
