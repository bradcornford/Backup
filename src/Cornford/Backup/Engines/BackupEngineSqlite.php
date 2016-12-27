<?php namespace Cornford\Backup\Engines;

class BackupEngineSqlite extends BackupEngineAbstract {

	const ENGINE_NAME = 'sqlite';
	const ENGINE_EXTENSION = 'sqlite';
	const ENGINE_EXPORT_PROCESS = 'cp';
	const ENGINE_RESTORE_PROCESS = 'cp';

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
		$command = sprintf('%s %s %s',
			$this->getExportCommand(),
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
		$command = sprintf('%s -f %s %s',
			$this->getRestoreCommand(),
			escapeshellarg($filepath),
			escapeshellarg($this->getDatabase())
		);

		return $this->getBackupProcess()->run($command, __FUNCTION__);
	}

}