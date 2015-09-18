<?php namespace Cornford\Backup\Contracts;

use Cornford\Backup\BackupProcess;

interface BackupEngineInterface {

	/**
	 * Backup engine constructor.
	 *
	 * @param BackupProcess $backupProcess
	 * @param string        $hostname
	 * @param string        $port
	 * @param string        $database
	 * @param string        $username
	 * @param string        $password
	 * @param array         $options
	 */
	public function __construct(
		BackupProcess $backupProcess,
		$database,
		$hostname = null,
		$port = null,
		$username = null,
		$password = null,
		array $options = []
	);

	/**
	 * Set backup process instance.
	 *
	 * @param BackupProcess $value
	 *
	 * @return void
	 */
	public function setBackupProcess(BackupProcess $value);

	/**
	 * Get backup process instance.
	 *
	 * @return BackupProcess
	 */
	public function getBackupProcess();

	/**
	 * Set database export command.
	 *
	 * @param string $value
	 *
	 * @return void
	 */
	public function setExportCommand($value);

	/**
	 * Get database export command.
	 *
	 * @return string
	 */
	public function getExportCommand();

	/**
	 * Set database restore command.
	 *
	 * @param string $value
	 *
	 * @return void
	 */
	public function setRestoreCommand($value);

	/**
	 * Get database restore command.
	 *
	 * @return string
	 */
	public function getRestoreCommand();

	/**
	 * Set database name.
	 *
	 * @param string $value
	 *
	 * @return void
	 */
	public function setDatabase($value);

	/**
	 * Get database name.
	 *
	 * @return string
	 */
	public function getDatabase();

	/**
	 * Set database hostname.
	 *
	 * @param string $value
	 *
	 * @return void
	 */
	public function setHostname($value);

	/**
	 * Get database hostname.
	 *
	 * @return string
	 */
	public function getHostname();

	/**
	 * Set database port.
	 *
	 * @param string $value
	 *
	 * @return void
	 */
	public function setPort($value);

	/**
	 * Get database port.
	 *
	 * @return string
	 */
	public function getPort();

	/**
	 * Set database username.
	 *
	 * @param string $value
	 *
	 * @return void
	 */
	public function setUsername($value);

	/**
	 * Get database username.
	 *
	 * @return string
	 */
	public function getUsername();

	/**
	 * Set database password.
	 *
	 * @param string $value
	 *
	 * @return void
	 */
	public function setPassword($value);

	/**
	 * Get database password.
	 *
	 * @return string
	 */
	public function getPassword();

	/**
	 * Set database options.
	 *
	 * @param array $value
	 *
	 * @return self
	 */
	public function setOptions($value);

	/**
	 * Get database options.
	 *
	 * @return array
	 */
	public function getOptions();

	/**
	 * Get export process.
	 *
	 * @return string
	 */
	public function getExportProcess();

	/**
	 * Get restore process.
	 *
	 * @return string
	 */
	public function getRestoreProcess();

	/**
	 * Get database file extension.
	 *
	 * @return string
	 */
	public function getFileExtension();

	/**
	 * Export the database to a file path.
	 *
	 * @param string $filepath
	 *
	 * @return boolean
	 */
	public function export($filepath);

	/**
	 * Restore the database from a file path.
	 *
	 * @param string $filepath
	 *
	 * @return boolean
	 */
	public function restore($filepath);

}
