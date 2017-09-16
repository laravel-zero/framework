<?php

namespace LaravelZero\Framework\Providers\Composer;

use LaravelZero\Framework\Contracts\Providers\Composer as ComposerContract;

/**
 * This is the Zero Framework composer class.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
class Composer implements ComposerContract
{
    /**
     * {@inheritdoc}
     */
    public function require(string $package): ComposerContract
    {
        exec('cd '.BASE_PATH." && composer require $package");

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function createProject(string $package, string $name, array $options): ComposerContract
    {
        $line = "composer create-project $package $name";

        collect($options)->each(function($option) use (&$line) {
            $line .= " $option";
        });

        exec("$line");

        return $this;
    }
}
