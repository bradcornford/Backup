<?php namespace Cornford\Backup;

use Cornford\Backup\Contracts\BackupFilesystemInterface;

class BackupFilesystem implements BackupFilesystemInterface {

	const OS_UNKNOWN = 1;
	const OS_WIN = 2;
	const OS_LINUX = 3;
	const OS_OSX = 4;

	/**
	 * Ignored files.
	 *
	 * @var array
	 */
	public static $ignoredFiles = [
		'.gitignore',
		'.gitkeep'
	];

	/**
	 * Check path exists.
	 *
	 * @param string $path
	 *
	 * @return boolean
	 */
	public function checkPathExists($path)
	{
		if (!is_dir($path)) {
			return false;
		}

		return true;
	}

	/**
	 * Check file exists.
	 *
	 * @param string $filepath
	 *
	 * @return boolean
	 */
	public function checkFileExists($filepath)
	{
		if (!$this->checkPathExists(dirname($filepath))) {
			return false;
		}

		if (!is_file($filepath)) {
			return false;
		}

		return true;
	}

	/**
	 * Check if file is empty.
	 *
	 * @param string $filepath
	 *
	 * @return boolean
	 */
	public function checkFileEmpty($filepath)
	{
		return filesize($filepath) == 0;
	}

	/**
	* Remove file.
	*
	* @param string $filepath
	*
	* @return void
	*/
	public function removeFile($filepath)
	{
		@unlink($filepath);
	}

	/**
	* Write a compressed file.
	*
	* @param string $compressedFilepath
	* @param string $filepath
	*
	* @return void
	*/
	public function writeCompressedFile($compressedFilepath, $filepath)
	{
		$filePointer = fopen($compressedFilepath, 'w');
		fwrite($filePointer, gzencode(file_get_contents($filepath), 9));
		fclose($filePointer);
	}

	/**
	* Write an uncompressed file.
	*
	* @param string $uncompressedFilepath
	* @param string $filepath
	*
	* @return void
	*/
	public function writeUncompressedFile($uncompressedFilepath, $filepath)
	{
		$filePointer = fopen($uncompressedFilepath, 'w');
		fwrite($filePointer, gzdecode(file_get_contents($filepath, 9)));
		fclose($filePointer);
	}

	/**
	 * Get the operating system.
	 *
	 * @return integer
	 */
	public function getOperatingSystem()
	{
		switch (true) {
			case stristr(PHP_OS, 'DAR'):
				return self::OS_OSX;
			case stristr(PHP_OS, 'WIN'):
				return self::OS_WIN;
			case stristr(PHP_OS, 'LINUX'):
				return self::OS_LINUX;
			default:
				return self::OS_UNKNOWN;
		}
	}

	/**
	 * Locate command location.
	 *
	 * @param string $command
	 *
	 * @return string|false
	 */
	public function locateCommand($command)
	{
		switch ($this->getOperatingSystem()) {
			case self::OS_OSX:
			case self::OS_LINUX:
				exec(sprintf('/usr/bin/which %s', $command), $result, $returnCode);
				if (isset($result[0])) {
					$result = $result[0];
				}
				break;
			case self::OS_WIN:
				exec(sprintf('where %s', $command), $result, $returnCode);
				if (isset($result[0])) {
					$result = $result[0];
				}
				break;
			default:
				return false;
		}

		if (empty($result)) {
			return false;
		}

		return $result;
	}

	/**
	* Check a function exists.
	*
	* @param string $function
	*
	* @return boolean
	*/
	public function checkFunctionExists($function)
	{
		return function_exists($function);
	}

}