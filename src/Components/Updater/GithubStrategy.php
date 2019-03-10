<?php

namespace LaravelZero\Framework\Components\Updater;

use Phar;

/**
 * @internal
 */
final class GithubStrategy extends \Humbug\SelfUpdate\Strategy\GithubStrategy
{
    /**
     * Returns the Download Url.
     *
     * @param array $package
     *
     * @return string
     */
    protected function getDownloadUrl(array $package): string
    {
        $downloadUrl = parent::getDownloadUrl($package);

        $downloadUrl = str_replace('releases/download', 'raw', $downloadUrl);

        return $downloadUrl.'/builds/'.basename(Phar::running());
    }
}
