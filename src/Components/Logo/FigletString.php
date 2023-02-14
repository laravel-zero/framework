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

namespace LaravelZero\Framework\Components\Logo;

use InvalidArgumentException;
use Laminas\Text\Figlet\Figlet;

/**
 * @internal
 */
final class FigletString
{
    private string $string;

    private Figlet $figlet;

    public const DEFAULT_FONT = __DIR__.DIRECTORY_SEPARATOR.'fonts'.DIRECTORY_SEPARATOR.'big.flf';

    public function __construct(string $string, array $options)
    {
        $this->string = $string;
        $this->figlet = new Figlet();

        $this->parseOptions($options);
    }

    private function parseOptions(array $config): void
    {
        $this
            ->font($config['font'] ?? self::DEFAULT_FONT)
            ->outputWidth($config['outputWidth'] ?? 80)
            ->justification($config['justification'] ?? null)
            ->rightToLeft($config['rightToLeft'] ?? null);
    }

    private function font(?string $font): self
    {
        if (is_null($font)) {
            return $this;
        }

        $this->figlet->setFont($font);

        return $this;
    }

    private function outputWidth(int $outputWidth): self
    {
        $this->figlet->setOutputWidth($outputWidth);

        return $this;
    }

    private function justification(?string $justification): self
    {
        switch ($justification) {
            case 'left':
                $this->figlet->setJustification(Figlet::JUSTIFICATION_LEFT);
                break;
            case 'center':
                $this->figlet->setJustification(Figlet::JUSTIFICATION_CENTER);
                break;
            case 'right':
                $this->figlet->setJustification(Figlet::JUSTIFICATION_RIGHT);
                break;
            case null:
                // Let Figlet handle the justification
                break;
            default:
                throw new InvalidArgumentException('Invalid value given for the `logo.justification` option');
        }

        return $this;
    }

    private function rightToLeft(?string $rightToLeft): void
    {
        switch ($rightToLeft) {
            case 'right-to-left':
                $this->figlet->setRightToLeft(Figlet::DIRECTION_RIGHT_TO_LEFT);
                break;
            case 'left-to-right':
                $this->figlet->setRightToLeft(Figlet::DIRECTION_LEFT_TO_RIGHT);
                break;
            case null:
                // Let Figlet handle this
                break;
            default:
                throw new \InvalidArgumentException('Invalid value given for the `logo.rightToLeft` option');
        }
    }

    public function __toString()
    {
        $rendered = $this->figlet->render($this->string);

        return "\n{$rendered}\n";
    }
}
