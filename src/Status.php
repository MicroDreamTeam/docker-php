<?php

namespace Itwmw\Docker;

/**
 * ContainerState stores container's running state. It's part of ContainerJSONBase and will be returned by the "inspect" command.
 */
class Status
{
    public function __construct(public array $status)
    {
    }

    /**
     * String representation of the container state. Can be one of "created", "running", "paused", "restarting", "removing", "exited", or "dead".
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status['Status'] ?? null;
    }

    /**
     * Whether this container is running.
     *
     * Note that a running container can be paused. The `Running` and `Paused` booleans are not mutually exclusive:
     *
     * When pausing a container (on Linux), the freezer cgroup is used to suspend all processes in the container.
     * Freezing the process requires the process to be running. As a result, paused containers are both `Running` and `Paused`.
     *
     * Use the `Status` field instead to determine if a container's state is "running".
     * @return bool
     */
    public function isRunning(): bool
    {
        return $this->status['Running'] ?? false;
    }

    /**
     * Whether this container is paused.
     * @return bool
     */
    public function isPaused(): bool
    {
        return $this->status['Paused'] ?? false;
    }

    /**
     * Whether this container is restarting.
     * @return bool
     */
    public function isRestarting(): bool
    {
        return $this->status['Restarting'] ?? false;
    }

    /**
     * All Suatus
     * @return array
     */
    public function getAll(): array
    {
        return $this->status;
    }
}
