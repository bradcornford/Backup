<?php namespace Cornford\Backup\Contracts;

interface BackupFilesystemInterface {

	/**
	 * Check path exists.
	 *
	 * @param string $path
	 *
	 * @return boolean
	 */
	public function checkPathExists($path);

	/**
	 * Check file exists.
	 *
	 * @param string $filepath
	 *
	 * @return boolean
	 */
	public function checkFileExists($filepath);

	/**
	 * Remove file.
	 *
	 * @param string $filepath
	 *
	 * @return void
	 */
	public function removeFile($filepath);

	/**
	 * Write a compressed file.
	 *
	 * @param string $compressedFilepath
	 * @param string $filepath
	 *
	 * @return void
	 */
	public function writeCompressedFile($compressedFilepath, $filepath);

	/**
	 * Write an uncompressed file.
	 *
	 * @param string $uncompressedFilepath
	 * @param string $filepath
	 *
	 * @return void
	 */
	public function writeUncompressedFile($uncompressedFilepath, $filepath);

	/**
	 * Check a function exists.
	 *
	 * @param string $function
	 *
	 * @return boolean
	 */
	public function checkFunctionExists($function);

}
