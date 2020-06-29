<?php

namespace spec\Cornford\Backup\Commands;

use PhpSpec\ObjectBehavior;
use Mockery;
use Prophecy\Argument;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Helper\HelperSet;
use Cornford\Backup\Backup;
use Cornford\Backup\BackupFactory;
use Illuminate\Config\Repository;
use Cornford\Backup\Commands\BackupCommandRestore;
use Cornford\Backup\Contracts\BackupInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Illuminate\Contracts\Foundation\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Question\ChoiceQuestion;

class BackupCommandRestoreSpec extends ObjectBehavior
{
    public function let()
    {
        $backup = Mockery::mock(Backup::class);
        $backup->shouldReceive('setEnabled');
        $backup->shouldReceive('setPath');
        $backup->shouldReceive('getRestorationFiles')->andReturn(['filepath' => 'filename']);
        $backup->shouldReceive('getWorkingFilepath')->andReturn('123');
        $backup->shouldReceive('restore')->andReturn(true);

        $backupFactory = Mockery::mock(BackupFactory::class);
        $backupFactory->shouldReceive('build')->andReturn($backup);

        $configInstance = Mockery::mock(Repository::class);
        $configInstance->shouldReceive('get')->andReturn([]);

        $this->beConstructedWith($backupFactory, $configInstance);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(BackupCommandRestore::class);
    }

    public function it_should_get_a_backup_instance()
    {
        $this->getBackupInstance()->shouldHaveType(BackupInterface::class);
    }

    public function it_should_execute_when_calling_fire_action(QuestionHelper $question, HelperSet $helpers)
    {
        $output = Mockery::mock(SymfonyStyle::class);
        $output->shouldReceive('askQuestion')->andReturn($question);
        $output->shouldReceive('writeln');

        $app = Mockery::mock(Application::class);
        $app->shouldReceive('call')->andReturn(true);
        $app->shouldReceive('make')->andReturn($output);

        $input = Mockery::mock(ArrayInput::class);
        $input->shouldReceive('bind');
        $input->shouldReceive('isInteractive')->andReturn(false);
        $input->shouldReceive('hasArgument')->andReturn(false);
        $input->shouldReceive('validate')->andReturn(false);
        $input->shouldReceive('getOption')->andReturn(false);

        $this->setLaravel($app);
        $helpers->get('question')->willReturn($question);

        $query = Argument::type(ChoiceQuestion::class);
        $question->ask($input, $output, $query)->willReturn(1);
        $helpers->get('question')->willReturn($question);
        $this->setHelperSet($helpers);
        $this->run($input, $output);
        $this->fire();
    }
}
