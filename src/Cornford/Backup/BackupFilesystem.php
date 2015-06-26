<?php namespace Cornford\Backup;

use Cornford\Backup\Contracts\BackupFilesystemInterface;

class BackupFilesystem implements BackupFilesystemInterface {

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
	* Remove file.
	*
	* @param string $filepath
	*
	* @return void
	*/
	public function removeFile($filepath)
	{
		unlink($filepath);
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