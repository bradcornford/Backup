<?php

namespace Cornford\Backup\Commands;

use Exception;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class BackupCommandExport extends BackupCommandAbstract
{

    /**
     * Name.
     *
     * @var string
     */
    protected $name = 'db:export';

    /**
     * Description.
     *
     * @var string
     */
    protected $description = 'Export the default database to `app/storage/backup`';

    /**
     * Handle.
     *
     * @return void
     */
    public function handle(): void
    {
        $backupInstance = $this->getBackupInstance($this->input->getOption('database'));
        $backupInstance->setEnabled(true);

        if ($this->input->getOption('path') !== null) {
            $backupInstance->setPath($this->input->getOption('path'));
        } else {
            $backupInstance->setPath('app/storage/backup/');
        }

        if ($this->input->getOption('compress') !== null) {
            $backupInstance->setCompress($this->input->getOption('compress') != 'false' ?: false);
        }

        if ($this->argument('filename') !== null) {
            $backupInstance->setFilename($this->argument('filename'));
        }

        try {
            if (!$backupInstance->export()) {
                throw new Exception();
            }
        } catch (Exception $exception) {
            $this->error('An error occurred exporting the database.');

            return;
        }

        $this->info('Database exported to file: "' . $backupInstance->getWorkingFilepath() . '".');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments(): array
    {
        return [
            ['filename', InputArgument::OPTIONAL, 'Filename for the database export file.']
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            ['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to backup'],
            ['path', null, InputOption::VALUE_OPTIONAL, 'The database export path'],
            ['compress', null, InputOption::VALUE_OPTIONAL, 'Compress the database export using gzip'],
        ];
    }
}
