<?php

namespace LaravelZero\Framework\Components\Updater\Strategy;

interface StrategyInterface extends \Humbug\SelfUpdate\Strategy\StrategyInterface
{
    public function setPackageName($name);

    public function setCurrentLocalVersion($version);
}
