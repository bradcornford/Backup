<?php

namespace Cornford\Backup\Contracts;

use Cornford\Backup\Command;
use Cornford\Backup\Exceptions\BackupExportException;
use Cornford\Backup\Exceptions\BackupRestoreException;
use Symfony\Component\Process\Process;

interface BackupProcessInterface
{
    /**
     * Backup process constructor.
     *
     * @param Process $process
     */
    public function __construct(Process $process);

    /**
     * Set an instance of process.
     *
     * @param Process $process
     *
     * @return void
     */
    public function setProcessInstance(Process $process);

    /**
     * Get an instance of process.
     *
     * @return Process
     */
    public function getProcessInstance();

    /**
     * Set a process timeout.
     *
     * @param int $timeout
     *
     * @return void
     */
    public function setProcessTimeout($timeout);

    /**
     * Get a process timeout.
     *
     * @return int
     */
    public function getProcessTimeout();

    /**
     * Execute a command process.
     *
     * @param string $command
     * @param string $operation
     *
     * @throws BackupExportException
     * @throws BackupRestoreException
     *
     * @return bool
     */
    public function run($command, $operation);

    /**
     * Get command output.
     *
     * @return string
     */
    public function getOutput();
}
