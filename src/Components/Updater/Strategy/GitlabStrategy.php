<?php

namespace LaravelZero\Framework\Components\Updater\Strategy;

use LaravelZero\Framework\Components\Updater\Strategy\Concerns\UsesPharName;

class GitlabStrategy extends \Humbug\SelfUpdate\Strategy\GithubStrategy implements StrategyInterface
{
    use UsesPharName;

    protected function getDownloadUrl(array $package): string
    {
        $downloadUrl = parent::getDownloadUrl($package);

        $downloadUrl = str_replace('releases/download', '-/raw', $downloadUrl);

        return "{$downloadUrl}/builds/{$this->getPharName()}";
    }
}
