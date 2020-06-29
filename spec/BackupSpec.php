<?php

namespace spec\Cornford\Backup;

use Cornford\Backup\BackupFilesystem;
use Cornford\Backup\Contracts\BackupInterface;
use Cornford\Backup\Engines\BackupEngineMysql;
use Cornford\Backup\Exceptions\BackupExportException;
use Cornford\Backup\Exceptions\BackupRestoreException;
use PhpSpec\ObjectBehavior;
use Mockery;
use Cornford\Backup\Exceptions\BackupException;

class BackupSpec extends ObjectBehavior
{
    private $options = [];
    private $engineInstance;
    private $filesystemInstance;

    public function let()
    {
        $processor = [
            'export' => '/usr/bin/',
            'restore' => '/usr/bin/'
        ];

        $this->options['enabled'] = true;
        $this->options['path'] = '/';
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

        $this->engineInstance = Mockery::mock(BackupEngineMysql::class);
        $this->engineInstance->shouldReceive('export')->andReturn(true);
        $this->engineInstance->shouldReceive('restore')->andReturn(true);
        $this->engineInstance->shouldReceive('getFileExtension')->andReturn('sql');
        $this->engineInstance->shouldReceive('setExportCommand', [$processor['export']])->andReturn('sql');
        $this->engineInstance->shouldReceive('setRestoreCommand', [$processor['restore']])->andReturn('sql');
        $this->engineInstance->shouldReceive('getExportProcess')->andReturn('mysqldump');
        $this->engineInstance->shouldReceive('getRestoreProcess')->andReturn('mysql');
        $this->engineInstance->shouldReceive('getProcessOutput')->andReturn('output');

        $this->filesystemInstance = Mockery::mock(BackupFilesystem::class);
        $this->filesystemInstance->shouldReceive('removeFile')->andReturn();
        $this->filesystemInstance->shouldReceive('writeCompressedFile')->andReturn();
        $this->filesystemInstance->shouldReceive('writeUncompressedFile')->andReturn();
        $this->filesystemInstance->shouldReceive('checkFileExists')->andReturn(true);
        $this->filesystemInstance->shouldReceive('checkPathExists')->andReturn(true);
        $this->filesystemInstance->shouldReceive('checkFunctionExists')->andReturn(true);
        $this->filesystemInstance->shouldReceive('checkFileEmpty')->andReturn(false);
        $this->filesystemInstance->shouldReceive('locateCommand', ['mysql'])->andReturn('/usr/bin/');

        $this->beConstructedWith($this->engineInstance, $this->filesystemInstance, $this->options);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(BackupInterface::class);
    }

    public function it_should_allow_a_backup_engine_to_be_set_and_got()
    {
        $this->setBackupEngineInstance($this->engineInstance);
        $this->getBackupEngineInstance()->shouldReturn($this->engineInstance);
    }

    public function it_should_allow_a_backup_filesystem_to_be_set_and_got()
    {
        $this->setBackupFilesystemInstance($this->filesystemInstance);
        $this->getBackupFilesystemInstance()->shouldReturn($this->filesystemInstance);
    }

    public function it_should_allow_enabled_option_to_be_set_and_got()
    {
        $this->setEnabled(true);
        $this->getEnabled()->shouldReturn(true);
    }

    public function it_should_allow_path_option_to_be_set_and_got()
    {
        $this->setPath(__DIR__);
        $this->getPath()->shouldReturn(__DIR__);
    }

    public function it_should_allow_compress_option_to_be_set_and_got()
    {
        $this->setCompress(true);
        $this->getCompress()->shouldReturn(true);
    }

    public function it_should_allow_filename_option_to_be_set_and_got()
    {
        $this->setFilename('filename');
        $this->getFilename()->shouldReturn('filename');
    }

    public function it_should_return_the_current_working_path_when_working_with_compressed_files()
    {
        $this->setPath(__DIR__);
        $this->export('filename')->shouldReturn(true);
        $this->getWorkingFilepath()->shouldReturn(__DIR__ . '/filename.sql.gz');
    }

    public function it_should_return_the_current_working_path_when_not_working_with_compressed_files()
    {
        $this->setPath(__DIR__);
        $this->setCompress(false);
        $this->export('filename')->shouldReturn(true);
        $this->getWorkingFilepath()->shouldReturn(__DIR__ . '/filename.sql');
    }

    public function it_should_export_a_database()
    {
        $this->setPath(__DIR__);
        $this->export('filename')->shouldReturn(true);
    }

    public function it_should_restore_a_database()
    {
        $this->setPath(__DIR__);
        $this->restore('filename')->shouldReturn(true);
    }

    public function it_should_return_an_array_of_restoration_files()
    {
        $this->setPath('/path/to/file');
        $this->getRestorationFiles()->shouldReturn([]);
    }

    public function it_should_return_process_output()
    {
        $this->setPath(__DIR__);
        $this->export('filename')->shouldReturn(true);
        $this->getProcessOutput()->shouldReturn('output');
    }

    public function it_should_throw_an_exception_if_a_path_doesnt_exist_when_setting_a_path()
    {
        $filesystemInstance = Mockery::mock(BackupFilesystem::class);
        $filesystemInstance->shouldReceive('checkPathExists')->andReturn(false);
        $filesystemInstance->shouldReceive('locateCommand', ['mysql'])->andReturn('/usr/bin/');

        $this->beConstructedWith($this->engineInstance, $filesystemInstance, $this->options);

        $this->setPath('/path/to/file');
        $this->shouldThrow(BackupExportException::class)->during('export', ['filename']);
    }

    public function it_should_throw_an_exception_if_a_file_doesnt_exist_when_restoring_a_database()
    {
        $filesystemInstance = Mockery::mock(BackupFilesystem::class);
        $filesystemInstance->shouldReceive('checkFileExists')->andReturn(false);
        $filesystemInstance->shouldReceive('locateCommand', ['mysql'])->andReturn('/usr/bin/');

        $this->beConstructedWith($this->engineInstance, $filesystemInstance, $this->options);

        $this->setPath('/path/to/file');
        $this->shouldThrow(BackupRestoreException::class)->during('restore', ['filename']);
    }

    public function it_should_throw_an_exception_if_the_database_restoration_path_doesnt_exist()
    {
        $filesystemInstance = Mockery::mock(BackupFilesystem::class);
        $filesystemInstance->shouldReceive('checkPathExists')->andReturn(false);
        $filesystemInstance->shouldReceive('locateCommand', ['mysql'])->andReturn('/usr/bin/');

        $this->beConstructedWith($this->engineInstance, $filesystemInstance, $this->options);

        $this->shouldThrow(BackupException::class)->during('getRestorationFiles');
    }

    public function it_should_throw_an_exception_if_the_function_gzencode_doesnt_exist()
    {
        $filesystemInstance = Mockery::mock(BackupFilesystem::class);
        $filesystemInstance->shouldReceive('checkPathExists')->andReturn(true);
        $filesystemInstance->shouldReceive('checkFunctionExists', ['gzencode'])->andReturn(false);
        $filesystemInstance->shouldReceive('locateCommand', ['mysql'])->andReturn('/usr/bin/');

        $this->beConstructedWith($this->engineInstance, $filesystemInstance, $this->options);

        $this->setPath(__DIR__);
        $this->setCompress(true);
        $this->shouldThrow(BackupException::class)->during('export', ['filename']);
    }

    public function it_should_throw_an_exception_if_the_function_gzdecode_doesnt_exist()
    {
        $filesystemInstance = Mockery::mock(BackupFilesystem::class);
        $filesystemInstance->shouldReceive('checkFileExists')->andReturn(true);
        $filesystemInstance->shouldReceive('checkFunctionExists', ['gzencode'])->andReturn(false);
        $filesystemInstance->shouldReceive('checkFileEmpty')->andReturn(false);
        $filesystemInstance->shouldReceive('locateCommand', ['mysql'])->andReturn('/usr/bin/');

        $this->beConstructedWith($this->engineInstance, $filesystemInstance, $this->options);

        $this->setPath(__DIR__);
        $this->setCompress(true);
        $this->shouldThrow(BackupException::class)->during('restore', ['filename.sql.gz']);
    }
}
