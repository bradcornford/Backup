<?php namespace Cornford\Backup\Engines;

class BackupEngineSqlite extends BackupEngineAbstract {

	CONST ENGINE_NAME = 'sqlite';

	/**
	 * Get database file extension.
	 *
	 * @return string
	 */
	public function getFileExtension()
	{
		return 'sqlite';
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
		$command = sprintf('cp %s %s',
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
		$command = sprintf('cp -f %s %s',
			escapeshellarg($filepath),
			escapeshellarg($this->getDatabase())
		);

		return $this->getBackupProcess()->run($command, __FUNCTION__);
	}

}