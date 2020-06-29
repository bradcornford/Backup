<?php

namespace Cornford\Backup\Contracts;

use Cornford\Backup\BackupFactory;
use Illuminate\Config\Repository as Config;

interface BackupCommandInterface
{
    /**
     * Base command constructor.
     *
     * @param BackupFactory $backupFactory
     * @param Config        $configInstance
     */
    public function __construct(BackupFactory $backupFactory, Config $configInstance);

    /**
     * Get a backup instance.
     *
     * @param string $database
     *
     * @return BackupInterface
     */
    public function getBackupInstance($database = null): BackupInterface;

    /**
     * Handle.
     *
     * @return void
     */
    public function handle(): void;
}
