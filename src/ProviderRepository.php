<?php

/**
 * This file is part of Laravel Zero.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace LaravelZero\Framework;

use Illuminate\Foundation\ProviderRepository as BaseProviderRepository;

/**
 * This is the Laravel Zero ProviderRepository implementation.
 */
class ProviderRepository extends BaseProviderRepository
{
    /**
     * {@inheritdoc}
     */
    public function writeManifest($manifest)
    {
        return array_merge(['when' => []], $manifest);
    }
}
