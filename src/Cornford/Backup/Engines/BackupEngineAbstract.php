<?php namespace Cornford\Backup\Engines;

use Cornford\Backup\Contracts\BackupEngineInterface;
use Cornford\Backup\BackupProcess;

abstract class BackupEngineAbstract implements BackupEngineInterface {

	/**
	 * Command instance.
	 *
	 * @var \Cornford\Backup\BackupProcess
	 */
	protected static $backupProcessInstance;

	/**
	 * Database export command.
	 *
	 * @var string
	 */
	protected $exportCommand;

	/**
	 * Database restore command.
	 *
	 * @var string
	 */
	protected $restoreCommand;

	/**
	 * Database name.
	 *
	 * @var string
	 */
	protected $database;

	/**
	 * Database hostname.
	 *
	 * @var string
	 */
	protected $hostname;

	/**
	 * Database port.
	 *
	 * @var string
	 */
	protected $port;

	/**
	 * Database username.
	 *
	 * @var string
	 */
	protected $username;

	/**
	 * Database password.
	 *
	 * @var string
	 */
	protected $password;

	/**
	 * Database options.
	 *
	 * @var array
	 */
	protected $options;

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
	) {
		$this->setBackupProcess($backupProcess);
		$this->setDatabase($database);
		$this->setHostname($hostname);
		$this->setPort($port);
		$this->setUsername($username);
		$this->setPassword($password);
		$this->setOptions($options);
	}

	/**
	 * Set backup process instance.
	 *
	 * @param BackupProcess $value
	 *
	 * @return void
	 */
	public function setBackupProcess(BackupProcess $value)
	{
		self::$backupProcessInstance = $value;
	}

	/**
	 * Get backup process instance.
	 *
	 * @return BackupProcess
	 */
	public function getBackupProcess()
	{
		return self::$backupProcessInstance;
	}

	/**
	 * Set database export command.
	 *
	 * @param string $value
	 *
	 * @return self
	 */
	public function setExportCommand($value)
	{
		$this->exportCommand = $value;

		return $this;
	}

	/**
	 * Get database export command.
	 *
	 * @return string
	 */
	public function getExportCommand()
	{
		return $this->exportCommand;
	}

	/**
	 * Set database restore command.
	 *
	 * @param string $value
	 *
	 * @return self
	 */
	public function setRestoreCommand($value)
	{
		$this->restoreCommand = $value;

		return $this;
	}

	/**
	 * Get database restore command.
	 *
	 * @return string
	 */
	public function getRestoreCommand()
	{
		return $this->restoreCommand;
	}

	/**
	 * Set database name.
	 *
	 * @param string $value
	 *
	 * @return self
	 */
	public function setDatabase($value)
	{
		$this->database = $value;

		return $this;
	}

	/**
	 * Get database name.
	 *
	 * @return string
	 */
	public function getDatabase()
	{
		return $this->database;
	}

	/**
	 * Set database hostname.
	 *
	 * @param string $value
	 *
	 * @return self
	 */
	public function setHostname($value)
	{
		$this->hostname = $value;

		return $this;
	}

	/**
	 * Get database hostname.
	 *
	 * @return string
	 */
	public function getHostname()
	{
		return $this->hostname;
	}

	/**
	 * Set database port.
	 *
	 * @param string $value
	 *
	 * @return self
	 */
	public function setPort($value)
	{
		$this->port = $value;

		return $this;
	}

	/**
	 * Get database port.
	 *
	 * @return string
	 */
	public function getPort()
	{
		return $this->port;
	}

	/**
	 * Set database username.
	 *
	 * @param string $value
	 *
	 * @return self
	 */
	public function setUsername($value)
	{
		$this->username = $value;

		return $this;
	}

	/**
	 * Get database username.
	 *
	 * @return string
	 */
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * Set database password.
	 *
	 * @param string $value
	 *
	 * @return self
	 */
	public function setPassword($value)
	{
		$this->password = $value;

		return $this;
	}

	/**
	 * Get database password.
	 *
	 * @return string
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * Set database options.
	 *
	 * @param array $value
	 *
	 * @return self
	 */
	public function setOptions($value)
	{
		$this->options = $value;

		return $this;
	}

	/**
	 * Get database options.
	 *
	 * @return array
	 */
	public function getOptions()
	{
		return $this->options;
	}

	/**
	 * Get export process.
	 *
	 * @return string
	 */
	abstract public function getExportProcess();

	/**
	 * Get restore process.
	 *
	 * @return string
	 */
	abstract public function getRestoreProcess();

	/**
	 * Get database file extension.
	 *
	 * @return string
	 */
	abstract public function getFileExtension();

	/**
	 * Export the database to a file.
	 *
	 * @param string $filepath
	 *
	 * @return boolean
	 */
	abstract public function export($filepath);

	/**
	 * Restore the database from a file path.
	 *
	 * @param string $filepath
	 *
	 * @return boolean
	 */
	abstract public function restore($filepath);

	/**
	 * Get the process output.
	 *
	 * @return string
	 */
	public function getProcessOutput()
	{
		return self::$backupProcessInstance->getOutput();
	}
}