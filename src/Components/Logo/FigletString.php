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

use Zend\Text\Figlet\Figlet as ZendFiglet;

/**
 * @internal
 */
final class FigletString
{
    private $string;

    private $figlet;

    public function __construct(string $name, $config)
    {
        $this->string = $name;
        $this->figlet = new ZendFiglet();

        $this->parseOptions($config);
    }

    private function parseOptions($config)
    {
        $this
            ->font($config->get('logo.font'))
            ->outputWidth($config->get('logo.outputWidth'))
            ->justification($config->get('logo.justification'))
            ->rightToLeft($config->get('logo.rightToLeft'));
    }

    private function font($font)
    {
        if (is_null($font)) {
            return $this;
        }

        $this->figlet->setFont($font);

        return $this;
    }

    private function outputWidth($outputWidth)
    {
        $this->figlet->setOutputWidth($outputWidth);

        return $this;
    }

    private function justification($justification)
    {
        switch ($justification) {
            case 'left':
                $this->figlet->setJustification(ZendFiglet::JUSTIFICATION_LEFT);
                break;
            case 'center':
                $this->figlet->setJustification(ZendFiglet::JUSTIFICATION_LEFT);
                break;
            case 'right':
                $this->figlet->setJustification(ZendFiglet::JUSTIFICATION_LEFT);
                break;
            case null:
                // Let ZendFiglet handle the justification
                break;
            default:
                throw new \InvalidArgumentException('Invalid value given for the `logo.justification` option');
        }

        return $this;
    }

    private function rightToLeft($rightToLeft)
    {
        switch ($rightToLeft) {
            case 'right-to-left':
                $this->figlet->setRightToLeft(ZendFiglet::DIRECTION_RIGHT_TO_LEFT);
                break;
            case 'left-to-right':
                $this->figlet->setRightToLeft(ZendFiglet::DIRECTION_LEFT_TO_RIGHT);
                break;
            case null:
                // Let ZendFiglet handle this
                break;
            default:
                throw new \InvalidArgumentException('Invalid value given for the `logo.rightToLeft` option');
        }

        return $this;
    }

    public function __toString()
    {
        $rendered = $this->figlet->render($this->string);

        return "\n$rendered\n";
    }
}
