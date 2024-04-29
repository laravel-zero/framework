<?php

declare(strict_types=1);

namespace LaravelZero\Framework\Components\Updater\Strategy\Concerns;

trait UsesPharName
{
    private string $pharName;

    public function setPharName($name): void
    {
        $this->pharName = $name;
    }

    public function getPharName(): string
    {
        return $this->pharName;
    }
}
