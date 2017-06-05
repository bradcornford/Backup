<?php namespace Cornford\Backup;

use Cornford\Backup\Exceptions\BackupException;
use Cornford\Backup\Exceptions\BackupExecuteException;
use Cornford\Backup\Exceptions\BackupExportException;
use Cornford\Backup\Exceptions\BackupRestoreException;
use DirectoryIterator;
use Exception;

class Backup extends BackupAbstract {

	/**
	 * Export database to file.
	 *
	 * @param string $filename
	 *
	 * @throws BackupExportException
	 *
	 * @return boolean
	 */
	public function export($filename = null)
	{
		if (!$this->getEnabled()) {
			return false;
		}

		if ($filename === null) {
			$filename = $this->getFilename();
		}

		$path = $this->getPath();

		if (!$this->getBackupFilesystemInstance()->checkPathExists($path)) {
			throw new BackupExportException('Unable to export to path "' . $path . '" as it doesn\'t exist.');
		}

		$filepath = $path . '/' . $filename . '.' . $this->getBackupEngineInstance()->getFileExtension();

        $this->setWorkingFilepath($filepath);
		$result = $this->getBackupEngineInstance()->export($filepath);

		if ($result) {
			if ($this->getCompress()) {
				$filepath = $this->compressFile($filepath);
			}

			$this->setWorkingFilepath($filepath);
		} else {
			$this->removeTemporaryFiles($filepath, true);
			$this->setWorkingFilepath(null);
		}

		return $result;
	}

	/**
	 * Restore database from file path.
	 *
	 * @param string $filepath
	 *
	 * @throws BackupRestoreException
	 *
	 * @return boolean
	 */
	public function restore($filepath)
	{
		if (!$this->getEnabled()) {
			return false;
		}

		if (!$this->getBackupFilesystemInstance()->checkFileExists($filepath)) {
			throw new BackupRestoreException('Unable to restore file "' . $filepath . '" as it doesn\'t exist.');
		}

		$this->setWorkingFilepath($filepath);

		if ($this->getBackupFilesystemInstance()->checkFileEmpty($filepath)) {
			$this->getBackupFilesystemInstance()->removeFile($filepath);

			return false;
		}

		if ($this->isCompressed($filepath)) {
			$filepath = $this->decompressFile($filepath);
		}

		$result = $this->getBackupEngineInstance()->restore($filepath);

		$this->removeTemporaryFiles($this->getWorkingFilepath());

		return $result;
	}

	/**
	 * Is a file compressed?
	 *
	 * @param string $filepath
	 *
	 * @return boolean
	 */
	protected function isCompressed($filepath)
	{
		return pathinfo($filepath, PATHINFO_EXTENSION) === "gz";
	}

	/**
	 * Remove temporary files.
	 *
	 * @param string  $filepath
	 * @param boolean $force
	 *
	 * @return void
	 */
	protected function removeTemporaryFiles($filepath, $force = false)
	{
		if ($force || $filepath !== $this->getUncompressedFilepath($filepath)) {
			$this->getBackupFilesystemInstance()->removeFile($this->getUncompressedFilepath($filepath));
		}
	}

	/**
	 * Compress a file with gzip.
	 *
	 * @param string $filepath
	 *
	 * @throws BackupException
	 *
	 * @return string
	 */
	protected function compressFile($filepath)
	{
		if (!$this->getBackupFilesystemInstance()->checkFunctionExists('gzencode')) {
			throw new BackupException('The method: "gzencode" isn\'t currently enabled.');
		}

		$compressedFilepath = $this->getCompressedFilepath($filepath);
		$this->getBackupFilesystemInstance()->writeCompressedFile($compressedFilepath, $filepath);
		$this->getBackupFilesystemInstance()->removeFile($filepath);

		return $compressedFilepath;
	}

	/**
	 * Decompress a file with gzip.
	 *
	 * @param string $filepath
	 *
	 * @throws BackupException
	 *
	 * @return string
	 */
	protected function decompressFile($filepath)
	{
		if (!$this->getBackupFilesystemInstance()->checkFunctionExists('gzdecode')) {
			throw new BackupException('The method: "gzdecode" isn\'t currently enabled.');
		}

		$uncompressedFilepath = $this->getUncompressedFilepath($filepath);
		$this->getBackupFilesystemInstance()->writeUncompressedFile($uncompressedFilepath, $filepath);

		return $uncompressedFilepath;
	}

	/**
	 * Get an compressed filepath from a uncompressed filepath.
	 *
	 * @param string $filepath
	 *
	 * @return string
	 */
	protected function getCompressedFilepath($filepath)
	{
		return $filepath . '.gz';
	}

	/**
	 * Get an uncompressed filepath from a compressed filepath.
	 *
	 * @param string $filepath
	 *
	 * @return string
	 */
	protected function getUncompressedFilepath($filepath)
	{
		return preg_replace('|\.gz$|', '', $filepath);
	}

	/**
	 * Get database restoration files.
	 *
	 * @param string $path
	 *
	 * @throws BackupException
	 *
	 * @return array
	 */
	public function getRestorationFiles($path = null)
	{
		if ($path === null) {
			$path = $this->getPath();
		}

		$results = [];

		if (!$this->getBackupFilesystemInstance()->checkPathExists($path)) {
			throw new BackupException('Unable to get restoration files as path "' . $path . '" doesn\'t exist');
		}

		try {
			foreach (new DirectoryIterator($path) as $fileinfo) {
				if ($fileinfo->isDot() ||
					!$fileinfo->isFile() ||
					in_array($fileinfo->getFilename(), BackupFilesystem::$ignoredFiles) ||
					substr($fileinfo->getFilename(), 0, 1) == '.'
				) {
					continue;
				}

				$results[] = $fileinfo->getPathname();
			}
		} catch (Exception $exception) {
			// Exception thrown continue and return empty result set
		}

		return $results;
	}

	/**
	 * Get database process output.
	 *
	 * @return string
	 */
	public function getProcessOutput()
	{
		return self::$backupEngineInstance->getProcessOutput();
	}

}
