<?php

namespace LaravelZero\Framework\Components\Updater;

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

        return $downloadUrl;
    }
}
