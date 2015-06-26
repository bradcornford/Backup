<?php namespace Cornford\Backup\Engines;

class BackupEngineSqlsrv extends BackupEngineAbstract {

	CONST ENGINE_NAME = 'sqlsrv';

	/**
	 * Get database file extension.
	 *
	 * @return string
	 */
	public function getFileExtension()
	{
		return 'bak';
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
			'SqlCmd -E -S %s –Q "BACKUP DATABASE %s TO DISK=\'%s\'"',
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
		$command = sprintf('SqlCmd -E -S %s –Q "RESTORE DATABASE %s FROM DISK=\'D:BackupsMyDB.bak\'"',
			escapeshellarg($this->getHostname()),
			escapeshellarg($this->getDatabase()),
			escapeshellarg($filepath)
		);

		return $this->getBackupProcess()->run($command, __FUNCTION__);
	}

}