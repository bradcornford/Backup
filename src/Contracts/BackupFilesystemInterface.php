<?php

namespace Cornford\Backup\Contracts;

interface BackupFilesystemInterface
{
    /**
     * Check path exists.
     *
     * @param string $path
     *
     * @return bool
     */
    public function checkPathExists($path);

    /**
     * Check file exists.
     *
     * @param string $filepath
     *
     * @return bool
     */
    public function checkFileExists($filepath);

    /**
     * Check if file is empty.
     *
     * @param string $filepath
     *
     * @return bool
     */
    public function checkFileEmpty($filepath);

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
     * Get the operating system.
     *
     * @return int
     */
    public function getOperatingSystem();

    /**
     * Locate command location.
     *
     * @param string $command
     *
     * @return string|false
     */
    public function locateCommand($command);

    /**
     * Check a function exists.
     *
     * @param string $function
     *
     * @return bool
     */
    public function checkFunctionExists($function);
}
