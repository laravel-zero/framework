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

use LaravelZero\Framework\Components;
use LaravelZero\Framework\Components\AbstractInstaller;
use Symfony\Component\Console\Input\ArrayInput;

final class InstallCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected $signature = 'app:install {component? : The component name}';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Install optional components';

    /**
     * The list of components installers.
     *
     * @var array
     */
    private $componentInstallers = [
        'console-dusk' => Components\ConsoleDusk\Installer::class,
        'database' => Components\Database\Installer::class,
        'dotenv' => Components\Dotenv\Installer::class,
        'http' => Components\Http\Installer::class,
        'log' => Components\Log\Installer::class,
        'menu' => Components\Menu\Installer::class,
        'queue' => Components\Queue\Installer::class,
        'redis' => Components\Redis\Installer::class,
        'self-update' => Components\Updater\Installer::class,
        'view' => Components\View\Installer::class,
    ];

    public function handle()
    {
        $title = 'Laravel Zero - Component installer';

        $choices = [];
        foreach ($this->componentInstallers as $name => $componentClass) {
            $choices[$name] = $this->app->make($componentClass)->getDescription();
        }

        if (! $option = $this->argument('component')) {
            $option = $this->choice($title, $choices);
        }

        // @phpstan-ignore notIdentical.alwaysTrue
        if ($option !== null && ! empty($this->componentInstallers[$option])) {
            /** @var AbstractInstaller $command */
            $command = $this->app[$this->componentInstallers[$option]];

            $command->setLaravel($this->app);

            $command->setApplication($this->getApplication());

            $this->info("Installing {$option} component...");

            $command->run(new ArrayInput([]), $this->output);
        }
    }
}
