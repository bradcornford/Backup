<?php namespace spec\Cornford\Backup;

use PhpSpec\ObjectBehavior;
use Mockery;

class BackupFactorySpec extends ObjectBehavior {

	private $options = [];

	public function let()
	{
		$processor = [
			'export' => '/usr/bin/',
			'restore' => '/usr/bin/'
		];

		$this->options['enabled'] = true;
		$this->options['path'] = '../app/storage/backup/';
		$this->options['filename'] = 'backup-' . date('Ymd-His');
		$this->options['compress'] = true;
		$this->options['processors'] = [
			'mysql' => $processor,
			'pgsql' => $processor,
			'sqlite' => $processor,
			'sqlsrv' => $processor
		];
		$this->options['default'] = 'mysql';
		$this->options['connections']['mysql']['database'] = 'database';
		$this->options['connections']['mysql']['host'] = 'localhost';
		$this->options['connections']['mysql']['port'] = 3306;
		$this->options['connections']['mysql']['username'] = 'root';
		$this->options['connections']['mysql']['password'] = '';
		$this->options['connections']['pgsql']['database'] = 'database';
		$this->options['connections']['pgsql']['host'] = 'localhost';
		$this->options['connections']['pgsql']['username'] = 'root';
		$this->options['connections']['pgsql']['password'] = '';
		$this->options['connections']['sqlite']['database'] = 'database';
		$this->options['connections']['sqlsrv']['database'] = 'database';
		$this->options['connections']['sqlsrv']['host'] = 'localhost';
		$this->options['connections']['sqlsrv']['username'] = 'root';
		$this->options['connections']['sqlsrv']['password'] = '';
	}

	function it_is_initializable()
	{
		$this->shouldHaveType('Cornford\Backup\BackupFactory');
	}

	function it_can_build_a_backup_object()
	{
		$this->build($this->options)->shouldHaveType('Cornford\Backup\Contracts\BackupInterface');
	}

	function it_can_build_a_backup_object_with_a_backup_engine_object()
	{
		$this->buildBackup($this->buildBackupEngine($this->options), $this->buildBackupFilesystem(), $this->options)->shouldHaveType('Cornford\Backup\Contracts\BackupInterface');
	}

	function it_can_build_a_backup_filesystem_object()
	{
		$this->buildBackupFilesystem()->shouldHaveType('Cornford\Backup\Contracts\BackupFilesystemInterface');
	}

	function it_can_build_a_backup_engine_object()
	{
		$this->buildBackupEngine($this->options)->shouldHaveType('Cornford\Backup\Contracts\BackupEngineInterface');
	}

	function it_can_build_a_mysql_backup_engine_object()
	{
		$this->buildBackupEngine($this->options)->shouldHaveType('Cornford\Backup\Engines\BackupEngineMysql');
	}

	function it_can_build_a_pgsql_backup_engine_object()
	{
		$options = $this->options;
		$options['default'] = 'pgsql';
		$this->buildBackupEngine($options)->shouldHaveType('Cornford\Backup\Engines\BackupEnginePgsql');
	}

	function it_can_build_a_sqlite_backup_engine_object()
	{
		$options = $this->options;
		$options['default'] = 'sqlite';
		$this->buildBackupEngine($options)->shouldHaveType('Cornford\Backup\Engines\BackupEngineSqlite');
	}

	function it_can_build_a_sqlsrv_backup_engine_object()
	{
		$options = $this->options;
		$options['default'] = 'sqlsrv';
		$this->buildBackupEngine($options)->shouldHaveType('Cornford\Backup\Engines\BackupEngineSqlsrv');
	}

	function it_throws_an_exception_when_an_undefined_backup_engine_object_is_created()
	{
		$options = $this->options;
		$options['default'] = 'test';
		$this->shouldThrow('Cornford\Backup\Exceptions\BackupException')->during('buildBackupEngine', [$options]);
	}

	function it_throws_an_exception_when_no_enabled_setting_is_set_when_a_backup_object_is_created()
	{
		$options = $this->options;
		unset($options['enabled']);
		$this->shouldThrow('Cornford\Backup\Exceptions\BackupArgumentException')->during('buildBackup', [$this->buildBackupEngine($options), $this->buildBackupFilesystem(), $options]);
	}

	function it_throws_an_exception_when_no_path_setting_is_set_when_a_backup_object_is_created()
	{
		$options = $this->options;
		unset($options['path']);
		$this->shouldThrow('Cornford\Backup\Exceptions\BackupArgumentException')->during('buildBackup', [$this->buildBackupEngine($options), $this->buildBackupFilesystem(), $options]);
	}

	function it_throws_an_exception_when_no_filename_setting_is_set_when_a_backup_object_is_created()
	{
		$options = $this->options;
		unset($options['filename']);
		$this->shouldThrow('Cornford\Backup\Exceptions\BackupArgumentException')->during('buildBackup', [$this->buildBackupEngine($options), $this->buildBackupFilesystem(), $options]);
	}

	function it_throws_an_exception_when_no_compress_setting_is_set_when_a_backup_object_is_created()
	{
		$options = $this->options;
		unset($options['compress']);
		$this->shouldThrow('Cornford\Backup\Exceptions\BackupArgumentException')->during('buildBackup', [$this->buildBackupEngine($options), $this->buildBackupFilesystem(), $options]);
	}

	function it_throws_an_exception_when_no_processors_setting_is_set_when_a_backup_object_is_created()
	{
		$options = $this->options;
		unset($options['processors']);
		$this->shouldThrow('Cornford\Backup\Exceptions\BackupArgumentException')->during('buildBackup', [$this->buildBackupEngine($options), $this->buildBackupFilesystem(), $options]);
	}

}
