<?php

namespace spec\Cornford\Backup\Commands;

use PhpSpec\ObjectBehavior;
use Mockery;
use Symfony\Component\Console\Helper\HelperSet;
use Cornford\Backup\Backup;
use Cornford\Backup\BackupFactory;
use Illuminate\Config\Repository;
use Cornford\Backup\Commands\BackupCommandExport;
use Cornford\Backup\Contracts\BackupInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Illuminate\Contracts\Foundation\Application;
use Symfony\Component\Console\Input\ArrayInput;

class BackupCommandExportSpec extends ObjectBehavior
{
    public function let()
    {
        $backup = Mockery::mock(Backup::class);
        $backup->shouldReceive('setEnabled');
        $backup->shouldReceive('setPath');
        $backup->shouldReceive('getWorkingFilepath')->andReturn('');
        $backup->shouldReceive('setCompress');
        $backup->shouldReceive('setFilename');
        $backup->shouldReceive('export')->andReturn(true);

        $backupFactory = Mockery::mock(BackupFactory::class);
        $backupFactory->shouldReceive('build')->andReturn($backup);

        $configInstance = Mockery::mock(Repository::class);
        $configInstance->shouldReceive('get')->andReturn([]);

        $this->beConstructedWith($backupFactory, $configInstance);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(BackupCommandExport::class);
    }

    public function it_should_get_a_backup_instance()
    {
        $this->getBackupInstance()->shouldHaveType(BackupInterface::class);
    }

    public function it_should_execute_when_calling_fire_action(HelperSet $helpers)
    {
        $output = Mockery::mock(SymfonyStyle::class);
        $output->shouldReceive('writeln');

        $app = Mockery::mock(Application::class);
        $app->shouldReceive('call')->andReturn(true);
        $app->shouldReceive('make')->andReturn($output);

        $input = Mockery::mock(ArrayInput::class);
        $input->shouldReceive('bind');
        $input->shouldReceive('isInteractive')->andReturn(false);
        $input->shouldReceive('hasArgument')->andReturn(false);
        $input->shouldReceive('getArgument')->andReturn(false);
        $input->shouldReceive('validate')->andReturn(false);
        $input->shouldReceive('getOption')->andReturn(false);

        $this->setLaravel($app);

        $this->setHelperSet($helpers);
        $this->run($input, $output);
        $this->fire();
    }
}
