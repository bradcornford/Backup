<?php

namespace Cornford\Backup;

use Cornford\Backup\Contracts\BackupFactoryInterface;
use Cornford\Backup\Contracts\BackupEngineInterface;
use Cornford\Backup\Contracts\BackupFilesystemInterface;
use Cornford\Backup\Contracts\BackupInterface;
use Cornford\Backup\Engines\BackupEngineMysql;
use Cornford\Backup\Engines\BackupEnginePgsql;
use Cornford\Backup\Engines\BackupEngineSqlite;
use Cornford\Backup\Engines\BackupEngineSqlsrv;
use Cornford\Backup\Exceptions\BackupException;
use Cornford\Backup\Exceptions\BackupArgumentException;
use Symfony\Component\Process\Process;

class BackupFactory implements BackupFactoryInterface
{
    /**
     * Build a new Backup object and its dependencies.
     *
     * @param array  $options
     * @param string $database
     *
     * @throws BackupException
     *
     * @return BackupInterface
     */
    public function build(
        array $options = [],
        $database = null
    ): BackupInterface {
        $backupEngine = $this->buildBackupEngine($options, $database);
        $backupFilesystem = $this->buildBackupFilesystem();

        return $this->buildBackup($backupEngine, $backupFilesystem, $options);
    }

    /**
     * Build the Backup object.
     *
     * @param BackupEngineInterface     $backupEngine
     * @param BackupFilesystemInterface $backupFilesystem
     * @param array                     $options
     *
     * @throws BackupArgumentException
     *
     * @return BackupInterface
     */
    public function buildBackup(
        BackupEngineInterface $backupEngine,
        BackupFilesystemInterface $backupFilesystem,
        array $options = []
    ): BackupInterface {
        return new Backup($backupEngine, $backupFilesystem, $options);
    }

    /**
     * Build a new Backup Engine object.
     *
     * @param array  $options
     * @param string $database
     *
     * @throws BackupException
     *
     * @return BackupEngineInterface
     */
    public function buildBackupEngine(
        array $options = [],
        $database = null
    ): BackupEngineInterface {
        $backupProcessInstance = new BackupProcess(new Process([]));

        switch ($database ? $database : $options['default']) {
            case 'mysql':
                return new BackupEngineMysql(
                    $backupProcessInstance,
                    $options['connections'][$options['default']]['database'],
                    $options['connections'][$options['default']]['host'],
                    (
                        isset($options['connections'][$options['default']]['port']) ?
                            $options['connections'][$options['default']]['port'] :
                            3306
                    ),
                    $options['connections'][$options['default']]['username'],
                    $options['connections'][$options['default']]['password'],
                    $options
                );
            case 'pgsql':
                return new BackupEnginePgsql(
                    $backupProcessInstance,
                    $options['connections'][$options['default']]['database'],
                    $options['connections'][$options['default']]['host'],
                    null,
                    $options['connections'][$options['default']]['username'],
                    $options['connections'][$options['default']]['password'],
                    $options
                );
            case 'sqlite':
                return new BackupEngineSqlite(
                    $backupProcessInstance,
                    $options['connections'][$options['default']]['database'],
                    null,
                    null,
                    null,
                    null,
                    $options
                );
            case 'sqlsrv':
                return new BackupEngineSqlsrv(
                    $backupProcessInstance,
                    $options['connections'][$options['default']]['database'],
                    $options['connections'][$options['default']]['host'],
                    null,
                    $options['connections'][$options['default']]['username'],
                    $options['connections'][$options['default']]['password'],
                    $options
                );
            default:
                throw new BackupException('Database driver isn\'t supported.');
        }
    }

    /**
     * Build the Backup filesystem object.
     *
     * @return BackupFilesystemInterface
     */
    public function buildBackupFilesystem(): BackupFilesystemInterface
    {
        return new BackupFilesystem();
    }
}
