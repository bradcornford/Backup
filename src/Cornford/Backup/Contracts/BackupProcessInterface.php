<?php namespace Cornford\Backup\Contracts;

use Cornford\Backup\Command;
use Symfony\Component\Process\Process;

interface BackupProcessInterface {

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
	 * @param integer $timeout
	 *
	 * @return void
	 */
	public function setProcessTimeout($timeout);

	/**
	 * Get a process timeout.
	 *
	 * @return integer
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
	 * @return boolean
	 */
	public function run($command, $operation);

	/**
	 * Get command output.
	 *
	 * @return string
	 */
	public function getOutput();

}
