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

namespace LaravelZero\Framework;

use function array_merge;
use Illuminate\Foundation\ProviderRepository as BaseProviderRepository;

/**
 * @internal
 */
final class ProviderRepository extends BaseProviderRepository
{
    /**
     * {@inheritdoc}
     */
    public function writeManifest($manifest): array
    {
        return array_merge(['when' => []], $manifest);
    }
}
