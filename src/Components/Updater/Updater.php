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

use Exception;
use InvalidArgumentException;
use Humbug\SelfUpdate\Updater as PharUpdater;
use RuntimeException;

/**
 * @internal
 */
final class Updater
{

    /** @var \Humbug\SelfUpdate\Updater */
    private $updater;


    /** @var string */
    private $pharFileName;

    /** @var string */
    private $strategy = 'github';

    /**
     * Updater constructor.
     * @param string $pharFileName
     * @param array $options
     * @param \Humbug\SelfUpdate\Updater $updater
     */
    public function __construct(string $pharFileName, array $options, PharUpdater $updater)
    {
        $this->updater = $updater;
        $this->pharFileName = $pharFileName;

        $this->parseOptions($options);
    }

    /**
     *
     */
    public function update()
    {

        try {

            $result = $this->updater->update();

            if ($result) {
                printf(
                    'Updated from version %s to %s',
                    $this->updater->getOldVersion(),
                    $this->updater->getNewVersion()
                );
            } elseif (false === $this->updater->getNewVersion()) {
                $message = 'There are no stable builds available.'. PHP_EOL;
            } else {
                $message = 'You have the current stable build installed.' . PHP_EOL;
            }

            return $message;

        } catch (Exception $e) {
            throw new RuntimeException('Something happened while trying to update the app.');
        }

    }

    /**
     * @param array $config
     */
    private function parseOptions(array $config)
    {
        $this
            ->strategy($config['strategy'] ?? 'github')
            ->pharFileUrl($config['pharFileUrl'] ?? null)
            ->versionUrl($config['versionUrl'] ?? null)
            ->packageName($config['packageName'] ?? null)
            ->pharFileName($config['pharFileName'] ?? $this->pharFileName)
            ->version($config['version'] ?? null);
    }

    /**
     * @param string|null $packageName
     * @return $this
     */
    private function packageName(?string $packageName)
    {
        if( null === $packageName) {
            throw new InvalidArgumentException('Invalid value given for the `updater.packageName` option');
        }

        $this->updater->getStrategy()->setPackageName($packageName);

        return $this;
    }

    /**
     * @param string $pharFileName
     * @return $this
     */
    private function pharFileName(string $pharFileName)
    {
        $this->updater->getStrategy()->setPharName($pharFileName);

        return $this;
    }

    /**
     * @param string|null $version
     * @return $this
     */
    private function version(?string $version)
    {
        $this->updater->getStrategy()->setCurrentLocalVersion($version);

        return $this;
    }

    /**
     * @param string|null $strategy
     * @return $this
     */
    private function strategy(?string $strategy)
    {
        if($strategy === 'github') {
            $this->updater->setStrategy(PharUpdater::STRATEGY_GITHUB);
        }

        return $this;
    }

    /**
     * @param string|null $pharFileUrl
     * @return $this
     */
    private function pharFileUrl(?string $pharFileUrl)
    {
        if($this->strategy === 'basic') {
            if(null !== $pharFileUrl) {
                $this->updater->getStrategy()->setPharUrl($pharFileUrl);
            } elseif( null === $pharFileUrl) {
                throw new InvalidArgumentException('Invalid value given for the `updater.pharFileUrl` option');
            }
        }

        return $this;
    }

    /**
     * @param string|null $versionUrl
     * @return $this
     */
    private function versionUrl(?string $versionUrl)
    {
        if($this->strategy === 'basic') {
            if(null !== $versionUrl) {
                $this->updater->getStrategy()->setVersionUrl($versionUrl);
            } elseif( null === $versionUrl) {
                throw new InvalidArgumentException('Invalid value given for the `updater.versionUrl` option');
            }
        }

        return $this;
    }
}
