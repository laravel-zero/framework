<?php


namespace LaravelZero\Framework\Providers\CommandRecorder;


use Illuminate\Support\Collection;

class CommandRecorder
{
    const MODE_SILENT = 'silent';
    const MODE_DEFAULT = 'default';

    /**
     * @var Collection
     */
    protected $commandsCalled;

    /**
     * CommandRecorder constructor.
     */
    public function __construct()
    {
        $this->commandsCalled = new Collection;
    }

    /**
     * Clears the records.
     */
    public function reset()
    {
        $this->commandsCalled = new Collection;
    }

    /**
     * @return Collection
     */
    public function getCommandsCalled()
    {
        return $this->commandsCalled;
    }

    /**
     * Record a command call.
     *
     * @param $command
     * @param array $parameters
     * @param string $mode
     */
    public function record($command, $parameters = [], $mode = self::MODE_DEFAULT)
    {
        $this->commandsCalled[] = [
            'command' => $command,
            'parameters' => $parameters,
            'mode' => $mode,
        ];
    }

    /**
     * Determine if the command was called with the provided parameters.
     *
     * @param $command
     * @param array $parameters
     * @return bool
     */
    public function commandWasCalled($command, $parameters = [])
    {
        return $this->commandsCalled->contains(function($value) use ($command, $parameters) {
            return $value['command'] === $command && $value['parameters'] === $parameters;
        });
    }
}