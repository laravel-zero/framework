<?php

namespace LaravelZero\Framework\Components\Updater\Strategy;

use Phar;

class GitlabStrategy extends \Humbug\SelfUpdate\Strategy\GithubStrategy implements StrategyInterface
{
    /**
     * Returns the Download Url.
     */
    protected function getDownloadUrl(array $package): string
    {
        $downloadUrl = parent::getDownloadUrl($package);

        $downloadUrl = str_replace('releases/download', '-/raw', $downloadUrl);

        return $downloadUrl.'/builds/'.basename(Phar::running());
    }
}
