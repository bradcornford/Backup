<?php namespace Cornford\Backup\Engines;

class BackupEngineMysql extends BackupEngineAbstract {

	CONST ENGINE_NAME = 'mysql';

	/**
	 * Get database file extension.
	 *
	 * @return string
	 */
	public function getFileExtension()
	{
		return 'sql';
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
			'%smysqldump --user=%s --password=%s --host=%s --port=%s %s > %s',
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
			'%smysql --user=%s --password=%s --host=%s --port=%s %s < %s',
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