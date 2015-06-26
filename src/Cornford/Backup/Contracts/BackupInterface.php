<?php namespace Cornford\Backup\Contracts;

use Exception;

interface BackupInterface {

	/**
	 * Construct Backup object.
	 *
	 * @param BackupEngineInterface     $backupEngineInterface
	 * @param BackupFilesystemInterface $backupFilesystemInterface
	 * @param array                     $options
	 *
	 * @throws BackupArgumentException
	 */
	public function __construct(
		BackupEngineInterface $backupEngineInterface,
		BackupFilesystemInterface $backupFilesystemInterface,
		array $options = []
	);

	/**
	 * Set the backup engine instance.
	 *
	 * @param BackupEngineInterface $value
	 *
	 * @return void
	 */
	public function setBackupEngineInstance(BackupEngineInterface $value);

	/**
	 * Get the backup engine instance.
	 *
	 * @return BackupEngineInterface
	 */
	public function getBackupEngineInstance();

	/**
	 * Set the backup filesystem instance.
	 *
	 * @param BackupFilesystemInterface $value
	 *
	 * @return void
	 */
	public function setBackupFilesystemInstance(BackupFilesystemInterface $value);

	/**
	 * Get the backup filesystem instance.
	 *
	 * @return BackupFilesystemInterface
	 */
	public function getBackupFilesystemInstance();

	/**
	 * Set the backup enabled.
	 *
	 * @param boolean $value
	 *
	 * @return void
	 */
	public function setEnabled($value);

	/**
	 * Get the backup enabled.
	 *
	 * @return boolean
	 */
	public function getEnabled();

	/**
	 * Set the backup path.
	 *
	 * @param string $value
	 *
	 * @return void
	 */
	public function setPath($value);

	/**
	 * Get the backup path.
	 *
	 * @return string
	 */
	public function getPath();

	/**
	 * Set the backup compression state.
	 *
	 * @param boolean $value
	 *
	 * @return void
	 */
	public function setCompress($value);

	/**
	 * Get the backup compression state.
	 *
	 * @return boolean
	 */
	public function getCompress();

	/**
	 * Set the backup filename.
	 *
	 * @param string $value
	 *
	 * @return void
	 */
	public function setFilename($value);

	/**
	 * Get the backup filename.
	 *
	 * @return string
	 */
	public function getFilename();

	/**
	 * Get the filepath we are currently working with.
	 *
	 * @return string
	 */
	public function getWorkingFilepath();

	/**
	 * Export database to file.
	 *
	 * @param string $filename
	 *
	 * @return boolean
	 */
	public function export($filename = null);

	/**
	 * Restore database from file path.
	 *
	 * @param string $filepath
	 *
	 * @return boolean
	 */
	public function restore($filepath);

	/**
	 * Get database restoration files.
	 *
	 * @param string $path
	 *
	 * @return array
	 */
	public function getRestorationFiles($path = null);

	/**
	 * Get database backup process output.
	 *
	 * @return string
	 */
	public function getProcessOutput();

}
