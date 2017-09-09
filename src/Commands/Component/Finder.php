<?php

namespace LaravelZero\Framework\Commands\Component;

use LaravelZero\Framework\Contracts\Commands\Component\Finder as FinderContract;

/**
 * This is the Zero Framework component finder class.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
class Finder implements FinderContract
{
    /**
     * Finds all the available components.
     *
     * @return string[]
     */
    public function find(): array
    {
        $components = [];

        foreach ($this->folders(__DIR__) as $organization) {
            foreach ($this->folders($organization) as $project) {
                $components[] = $this->getProjectName($project);
            }
        }

        return $components;
    }

    /**
     * Returns all the folders of the provided dir.
     *
     * @param  string $dir
     *
     * @return string[]
     */
    private function folders(string $dir): array
    {
        return glob($dir.'/*', GLOB_ONLYDIR);
    }

    /**
     * Returns the project name in the form "vendor/package".
     *
     * @param  string $project
     *
     * @return string
     */
    private function getProjectName(string $project): string
    {
        $parts = explode('/', $project);

        $package = strtolower(array_pop($parts));
        $vendor = strtolower(last($parts));

        return "$vendor/$package";
    }
}
