<?php namespace Cornford\Backup\Engines;

class BackupEngineMysql extends BackupEngineAbstract {

	const ENGINE_NAME = 'mysql';
	const ENGINE_EXTENSION = 'sql';
	const ENGINE_EXPORT_PROCESS = 'mysqldump';
	const ENGINE_RESTORE_PROCESS = 'mysql';

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
			'%s --user=%s --password=%s --host=%s --port=%s %s > %s',
			$this->getExportCommand(),
			escapeshellarg($this->getUsername()),
			escapeshellarg($this->getPassword()),
			escapeshellarg($this->getHostname()),
			escapeshellarg($this->getPort()),
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
		$command = sprintf(
			'%s --user=%s --password=%s --host=%s --port=%s %s < %s',
			$this->getRestoreCommand(),
			escapeshellarg($this->getUsername()),
			escapeshellarg($this->getPassword()),
			escapeshellarg($this->getHostname()),
			escapeshellarg($this->getPort()),
			escapeshellarg($this->getDatabase()),
			escapeshellarg($filepath)
		);

		return $this->getBackupProcess()->run($command, __FUNCTION__);
	}

}