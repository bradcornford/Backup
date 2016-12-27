<?php namespace Cornford\Backup\Engines;

class BackupEnginePgsql extends BackupEngineAbstract {

	const ENGINE_NAME = 'pgsql';
	const ENGINE_EXTENSION = 'pgsql';
	const ENGINE_EXPORT_PROCESS = 'pg_dump';
	const ENGINE_RESTORE_PROCESS = 'pg_restore';

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
			'PGPASSWORD=%s %s -Fc --no-acl --no-owner -h %s -U %s %s > %s',
			escapeshellarg($this->getPassword()),
			$this->getExportCommand(),
			escapeshellarg($this->getHostname()),
			escapeshellarg($this->getUsername()),
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
		$command = sprintf('PGPASSWORD=%s %s --verbose --clean --no-acl --no-owner -h %s -U %s -d %s %s',
			escapeshellarg($this->getPassword()),
			$this->getRestoreCommand(),
			escapeshellarg($this->getHostname()),
			escapeshellarg($this->getUsername()),
			escapeshellarg($this->getDatabase()),
			escapeshellarg($filepath)
		);

		return $this->getBackupProcess()->run($command, __FUNCTION__);
	}

}