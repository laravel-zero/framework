<?php

namespace LaravelZero\Framework\Components\Updater\Strategy;

use Humbug\SelfUpdate\Updater;
use Humbug\SelfUpdate\Exception\HttpRequestException;

abstract class AbstractDirectDownloadStrategy implements StrategyInterface
{
    /** @var string */
    protected $localVersion;

    /** @var string */
    protected $packageName;

    abstract public function getDownloadUrl(): string;

    /** {@inheritdoc} */
    public function download(Updater $updater)
    {
        /** Switch remote request errors to HttpRequestExceptions */
        set_error_handler(array($updater, 'throwHttpRequestException'));
        $result = humbug_get_contents($this->getDownloadUrl());
        restore_error_handler();
        if (false === $result) {
            throw new HttpRequestException(sprintf(
                'Request to URL failed: %s',
                $this->getDownloadUrl()
            ));
        }

        file_put_contents($updater->getTempPharFile(), $result);
    }

    /** {@inheritdoc} */
    public function getCurrentRemoteVersion(Updater $updater)
    {
        return 'latest';
    }

    public function setCurrentLocalVersion(string $version): void
    {
        $this->localVersion = $version;
    }

    /** {@inheritdoc} */
    public function getCurrentLocalVersion(Updater $updater)
    {
        return $this->localVersion;
    }

    public function setPackageName(string $name): void
    {
        $this->packageName = $name;
    }

    public function getPackageName(): string
    {
        return $this->packageName;
    }
}
