<?php

namespace spec\Cornford\Backup\Engines;

use PhpSpec\ObjectBehavior;
use Mockery;
use Symfony\Component\Process\Process;
use Cornford\Backup\BackupProcess;
use Cornford\Backup\Engines\BackupEngineMysql;

class BackupEngineMysqlSpec extends ObjectBehavior
{
    private $options = [];

    public function let()
    {
        $symfonyProcess = Mockery::mock(Process::class);
        $symfonyProcess->shouldReceive('run');
        $symfonyProcess->shouldReceive('isSuccessful')->andReturn(true);
        $symfonyProcess->shouldReceive('setCommandLine')->andReturn($symfonyProcess);
        $symfonyProcess->shouldReceive('setTimeout')->andReturn($symfonyProcess);
        $symfonyProcess->shouldReceive('stop')->andReturn(1);

        $process = Mockery::mock(BackupProcess::class);
        $process->shouldReceive('__construct', [$symfonyProcess]);
        $process->shouldReceive('run')->andReturn(true);

        $processor = [
            'export' => '/usr/bin/',
            'restore' => '/usr/bin/'
        ];

        $this->options['enabled'] = true;
        $this->options['path'] = '../app/storage/backup/';
        $this->options['filename'] = 'backup-' . date('Ymd-His');
        $this->options['compress'] = true;
        $this->options['processors'] = [
            'mysql' => $processor
        ];

        $this->beConstructedWith($process, 'mysql', 'localhost', 3306, 'root', '', $this->options);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(BackupEngineMysql::class);
    }

    public function it_should_return_the_correct_file_extension()
    {
        $this->getFileExtension()->shouldReturn('sql');
    }

    public function it_should_return_successfully_when_running_an_export_command()
    {
        $this->export($this->options['filename'])->shouldReturn(true);
    }

    public function it_should_return_successfully_when_running_a_restore_command()
    {
        $this->restore($this->options['filename'])->shouldReturn(true);
    }
}
