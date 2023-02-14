<?php

namespace LaravelZero\Framework\Providers\CommandRecorder;

use Illuminate\Support\Collection;

/**
 * @internal
 */
final class CommandRecorderRepository
{
    /**
     * Command called in silence mode.
     */
    public const MODE_SILENT = 'silent';

    /**
     * Command called in default mode.
     */
    public const MODE_DEFAULT = 'default';

    /**
     * Holds the called commands.
     *
     * @var \Illuminate\Support\Collection
     */
    private $storage;

    /**
     * CommandRecorderRepository constructor.
     */
    public function __construct(Collection $storage = null)
    {
        $this->storage = $storage ?? collect();
    }

    /**
     * Clears the current history of called commands.
     */
    public function clear(): void
    {
        $this->storage = collect();
    }

    /**
     * Create a new entry of a called command in the storage.
     */
    public function create(string $command, array $arguments = [], string $mode = self::MODE_DEFAULT): void
    {
        $this->storage[] = [
            'command' => $command,
            'arguments' => $arguments,
            'mode' => $mode,
        ];
    }

    /**
     * Determine if the given command exists with the given arguments.
     */
    public function exists(string $command, array $arguments = []): bool
    {
        return $this->storage->contains(function ($value) use ($command, $arguments) {
            return $value['command'] === $command && $value['arguments'] === $arguments;
        });
    }
}
