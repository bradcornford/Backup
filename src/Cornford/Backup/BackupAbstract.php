<?php namespace Cornford\Backup;

use Cornford\Backup\Contracts\BackupEngineInterface;
use Cornford\Backup\Contracts\BackupFilesystemInterface;
use Cornford\Backup\Contracts\BackupInterface;
use Cornford\Backup\Exceptions\BackupArgumentException;

abstract class BackupAbstract implements BackupInterface {

	/**
	 * Backup engine instance.
	 *
	 * @var \Cornford\Backup\Contracts\BackupEngineInterface
	 */
	protected static $backupEngineInstance;

	/**
	 * Backup filesystem instance.
	 *
	 * @var \Cornford\Backup\Contracts\BackupFilesystemInterface
	 */
	protected $backupFilesystemInstance;

	/**
	 * Backup options.
	 *
	 * @var string
	 */
	protected $options;

	/**
	 * Backup enabled.
	 *
	 * @var boolean
	 */
	protected $enabled;

	/**
	 * Backup path.
	 *
	 * @var string
	 */
	protected $path;

	/**
	 * Backup compression.
	 *
	 * @var boolean
	 */
	protected $compress;

	/**
	 * Backup filename.
	 *
	 * @var string
	 */
	protected $filename;

	/**
	 * Backup working filepath.
	 *
	 * @var string
	 */
	protected $workingFilepath;

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
	) {
		$this->setBackupEngineInstance($backupEngineInterface);
		$this->setBackupFilesystemInstance($backupFilesystemInterface);
		$this->setOptions($options);

		if (!isset($options['enabled'])) {
			throw new BackupArgumentException('Database enabled setting is required.');
		}

		if (!isset($options['path'])) {
			throw new BackupArgumentException('Database backup path is required.');
		}

		if (!isset($options['filename'])) {
			throw new BackupArgumentException('Database backup filename is required.');
		}

		if (!isset($options['compress'])) {
			throw new BackupArgumentException('Database compression setting is required.');
		}

		if (!isset($options['processors'])) {
			throw new BackupArgumentException('Database engine processor settings are required.');
		}

		$this->setEnabled($this->options['enabled']);
		$this->setPath($this->options['path']);
		$this->setFilename($this->options['filename']);
		$this->setCompress($this->options['compress']);

		$exportCommand = $this->backupFilesystemInstance->locateCommand(self::$backupEngineInstance->getExportProcess());

		if (!$exportCommand) {
			$exportCommand = isset($options['processors'][$this->getBackupEngineName()]['export']) ? $options['processors'][$this->getBackupEngineName()]['export'] . $this->getBackupEngineInstance()->getExportProcess() : null;
		}

		$restoreCommand = $this->backupFilesystemInstance->locateCommand(self::$backupEngineInstance->getRestoreProcess());

		if (!$restoreCommand) {
			$restoreCommand = isset($options['processors'][$this->getBackupEngineName()]['restore']) ? $options['processors'][$this->getBackupEngineName()]['restore'] . $this->getBackupEngineInstance()->getRestoreProcess() : null;
		}

		self::$backupEngineInstance->setExportCommand($exportCommand);
		self::$backupEngineInstance->setRestoreCommand($restoreCommand);
	}

	/**
	 * Set the backup engine instance.
	 *
	 * @param BackupEngineInterface $value
	 *
	 * @return void
	 */
	public function setBackupEngineInstance(BackupEngineInterface $value)
	{
		self::$backupEngineInstance = $value;
	}

	/**
	 * Get the backup engine instance.
	 *
	 * @return BackupEngineInterface
	 */
	public function getBackupEngineInstance()
	{
		return self::$backupEngineInstance;
	}

	/**
	 * Set the backup filesystem instance.
	 *
	 * @param BackupFilesystemInterface $value
	 *
	 * @return void
	 */
	public function setBackupFilesystemInstance(BackupFilesystemInterface $value)
	{
		$this->backupFilesystemInstance = $value;
	}

	/**
	 * Get the backup filesystem instance.
	 *
	 * @return BackupFilesystemInterface
	 */
	public function getBackupFilesystemInstance()
	{
		return $this->backupFilesystemInstance;
	}

	/**
	 * Get the backup engine name.
	 *
	 * @return void
	 */
	private function getBackupEngineName()
	{
		$instance = self::$backupEngineInstance;

		return $instance::ENGINE_NAME;
	}

	/**
	 * Set the backup options.
	 *
	 * @param array $value
	 *
	 * @return void
	 */
	public function setOptions(array $value = [])
	{
		$this->options = $value;
	}

	/**
	 * Get the backup options.
	 *
	 * @return array
	 */
	public function getOptions()
	{
		return $this->options;
	}

	/**
	 * Set the backup enabled.
	 *
	 * @param boolean $value
	 *
	 * @return void
	 */
	public function setEnabled($value)
	{
		$this->enabled = $value;
	}

	/**
	 * Get the backup enabled.
	 *
	 * @return boolean
	 */
	public function getEnabled()
	{
		return $this->enabled;
	}

	/**
	 * Set the backup path.
	 *
	 * @param string $value
	 *
	 * @return void
	 */
	public function setPath($value)
	{
		$this->path = $value;
	}

	/**
	 * Get the backup path.
	 *
	 * @return string
	 */
	public function getPath()
	{
		$path = getcwd() . DIRECTORY_SEPARATOR . $this->path;

		if (substr($this->path, 0, 1) == '/' || substr($this->path, 1, 1) == ':') {
			$path = $this->path;
		}

		return realpath($path);
	}

	/**
	 * Set the backup compression state.
	 *
	 * @param boolean $value
	 *
	 * @return void
	 */
	public function setCompress($value)
	{
		$this->compress = $value;
	}

	/**
	 * Get the backup compression state.
	 *
	 * @return boolean
	 */
	public function getCompress()
	{
		return $this->compress;
	}

	/**
	 * Set the backup filename.
	 *
	 * @param string $value
	 *
	 * @return void
	 */
	public function setFilename($value)
	{
		$this->filename = $value;
	}

	/**
	 * Get the backup filename.
	 *
	 * @return string
	 */
	public function getFilename()
	{
		return $this->filename;
	}

	/**
	 * Set the filepath we are currently working with.
	 *
	 * @param string $filepath
	 *
	 * @return string
	 */
	protected function setWorkingFilepath($filepath)
	{
		$this->workingFilepath = $filepath;
	}

	/**
	 * Get the filepath we are currently working with.
	 *
	 * @return string
	 */
	public function getWorkingFilepath()
	{
		return $this->workingFilepath;
	}

	/**
	 * Export database to file.
	 *
	 * @param string $filename
	 *
	 * @return boolean
	 */
	abstract public function export($filename = null);

	/**
	 * Restore database from file path.
	 *
	 * @param string $filepath
	 *
	 * @return boolean
	 */
	abstract public function restore($filepath);

	/**
	 * Get database restoration files.
	 *
	 * @param string $path
	 *
	 * @return array
	 */
	abstract public function getRestorationFiles($path = null);

	/**
	 * Get database process output.
	 *
	 * @return string
	 */
	abstract public function getProcessOutput();
}
