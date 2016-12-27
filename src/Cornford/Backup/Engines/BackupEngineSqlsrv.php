<?php namespace Cornford\Backup\Engines;

class BackupEngineSqlsrv extends BackupEngineAbstract {

	const ENGINE_NAME = 'sqlsrv';
	const ENGINE_EXTENSION = 'bak';
	const ENGINE_EXPORT_PROCESS = 'SqlCmd';
	const ENGINE_RESTORE_PROCESS = 'SqlCmd';

	/**
	 * Get export process.
	 *
	 * @return string
	 */
	public function getExportProcess()
	{
		return self::ENGINE_EXPORT_PROCESS;
	}

	/**
	 * Get restore process.
	 *
	 * @return string
	 */
	public function getRestoreProcess()
	{
		return self::ENGINE_RESTORE_PROCESS;
	}

	/**
	 * Get database file extension.
	 *
	 * @return string
	 */
	public function getFileExtension()
	{
		return self::ENGINE_EXTENSION;
	}

	/**
	 * Export the database to a file path.
	 *
	 * @param string $filepath
	 *
	 * @return boolean
	 */
	public function export($filepath)
	{
		$command = sprintf(
			'%s -E -S %s –Q "BACKUP DATABASE %s TO DISK=\'%s\'"',
			$this->getExportCommand(),
			escapeshellarg($this->getHostname()),
			escapeshellarg($this->getDatabase()),
			escapeshellarg($filepath)
		);

		return $this->getBackupProcess()->run($command, __FUNCTION__);
	}

	/**
	 * Restore the database from a file path.
	 *
	 * @param string $filepath
	 *
	 * @return boolean
	 */
	public function restore($filepath)
	{
		$command = sprintf('%s -E -S %s –Q "RESTORE DATABASE %s FROM DISK=\'%s\'"',
			$this->getRestoreCommand(),
			escapeshellarg($this->getHostname()),
			escapeshellarg($this->getDatabase()),
			escapeshellarg($filepath)
		);

		return $this->getBackupProcess()->run($command, __FUNCTION__);
	}

}