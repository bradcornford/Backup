<?php

namespace Cornford\Backup;

use Cornford\Backup\Contracts\BackupProcessInterface;
use Cornford\Backup\Exceptions\BackupException;
use Cornford\Backup\Exceptions\BackupExportException;
use Cornford\Backup\Exceptions\BackupRestoreException;
use Exception;
use Symfony\Component\Process\Process;

class BackupProcess implements BackupProcessInterface
{
    private const PROCESS_TIMEOUT = 99999;

    /**
     * Process instance.
     *
     * @var \Symfony\Component\Process\Process
     */
    protected $processInstance;

    /**
     * Process timeout.
     *
     * @var int
     */
    protected $processTimeout = self::PROCESS_TIMEOUT;

    /**
     * Command output.
     *
     * @var string
     */
    protected $output;

    /**
     * Backup process constructor.
     *
     * @param Process $process
     */
    public function __construct(Process $process)
    {
        $this->setProcessInstance($process);
    }

    /**
     * Set an instance of process.
     *
     * @param Process $process
     *
     * @return void
     */
    public function setProcessInstance(Process $process): void
    {
        $this->processInstance = $process;
    }

    /**
     * Get an instance of process.
     *
     * @return Process
     */
    public function getProcessInstance(): Process
    {
        return $this->processInstance;
    }

    /**
     * Set a process timeout.
     *
     * @param int $timeout
     *
     * @return void
     */
    public function setProcessTimeout($timeout): int
    {
        $this->processTimeout = $timeout;
    }

    /**
     * Get a process timeout.
     *
     * @return int
     */
    public function getProcessTimeout(): int
    {
        return $this->processTimeout;
    }

    /**
     * Execute a command process.
     *
     * @param string $command
     * @param string $operation
     *
     * @throws BackupException
     * @throws BackupExportException
     * @throws BackupRestoreException
     *
     * @return bool
     */
    public function run($command, $operation = 'default'): bool
    {
        $this->output = null;

        try {
            $this->processInstance->setCommandLine($command);
            $this->processInstance->setTimeout(self::PROCESS_TIMEOUT);
            $this->processInstance->run();

            if (!$this->processInstance->isSuccessful()) {
                $this->output = $this->processInstance->getErrorOutput();

                return false;
            }
        } catch (Exception $exception) {
            $message = 'An error occurred that prevented the operation ' . $operation . ': ' . $exception->getMessage();

            switch ($operation) {
                case 'export':
                    throw new BackupExportException($message);
                case 'restore':
                    throw new BackupRestoreException($message);
                default:
                    throw new BackupException($message);
            }
        }

        return true;
    }

    /**
     * Get command output.
     *
     * @return string
     */
    public function getOutput(): string
    {
        return $this->output;
    }
}
