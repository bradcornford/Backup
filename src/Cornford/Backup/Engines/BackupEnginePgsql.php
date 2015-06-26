<?php namespace Cornford\Backup\Engines;

class BackupEnginePgsql extends BackupEngineAbstract {

	CONST ENGINE_NAME = 'pgsql';

	/**
	 * Get database file extension.
	 *
	 * @return string
	 */
	public function getFileExtension()
	{
		return 'pgsql';
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
			'PGPASSWORD=%s pg_dump -Fc --no-acl --no-owner -h %s -U %s %s > %s',
			escapeshellarg($this->getPassword()),
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
		$command = sprintf('PGPASSWORD=%s pg_restore --verbose --clean --no-acl --no-owner -h %s -U %s -d %s %s',
			escapeshellarg($this->getPassword()),
			escapeshellarg($this->getHostname()),
			escapeshellarg($this->getUsername()),
			escapeshellarg($this->getDatabase()),
			escapeshellarg($filepath)
		);

		return $this->getBackupProcess()->run($command, __FUNCTION__);
	}

}