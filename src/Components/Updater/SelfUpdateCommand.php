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

namespace LaravelZero\Framework\Components\Updater;

use LaravelZero\Framework\Commands\Command;

class SelfUpdateCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected $name = 'self-update';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Allows to self-update a build application';

    /**
     * {@inheritdoc}
     */
    public function handle(Updater $updater)
    {
        $this->output->title('Checking for a new version...');

        $updater->update($this->output);
    }
}
