<?php namespace Cornford\Backup\Commands;

use PhpSpec\Exception\Exception;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class BackupCommandRestore extends BackupCommandAbstract
{

	/**
	 * Name.
	 *
	 * @var string
	 */
	protected $name = 'db:restore';

	/**
	 * Description.
	 *
	 * @var string
	 */
	protected $description = 'Restore the default database from the given file';

	/**
	 * Fire.
	 *
	 * @return void
	 */
	public function fire()
	{
		$backupInstance = $this->getBackupInstance($this->input->getOption('database'));
		$backupInstance->setEnabled(true);

		if ($this->input->getOption('path') !== null) {
			$backupInstance->setPath($this->input->getOption('path'));
		} else {
			$backupInstance->setPath('app/storage/backup/');
		}

		$selection = $this->choice(
			'Which database restoration file should be used?',
			$backupInstance->getRestorationFiles()
		);

		try {
			if (!$backupInstance->restore($selection)) {
				throw new Exception();
			}
		} catch (Exception $exception) {
			$this->error('An error occurred exporting the database.');

			return false;
		}

		if (!$backupInstance->restore($selection)) {
			$this->error('An error occurred restoring the database.');

			return false;
		}

		$this->info('Restored database from file: "' . $backupInstance->getWorkingFilepath() . '".');
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['filename', InputArgument::OPTIONAL, 'Filepath for the database restoration file.']
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [
			['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to backup'],
			['path', null, InputOption::VALUE_OPTIONAL, 'The database export path']
		];
	}

}