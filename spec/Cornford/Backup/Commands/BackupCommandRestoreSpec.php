<?php namespace spec\Cornford\Backup\Commands;

use PhpSpec\ObjectBehavior;
use Mockery;
use Prophecy\Argument;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Helper\HelperSet;
use Illuminate\Console\Command;

class BackupCommandRestoreSpec extends ObjectBehavior {

	private $options = [];

	public function let()
	{
		$backup = Mockery::mock('Cornford\Backup\Backup');
		$backup->shouldReceive('setEnabled');
		$backup->shouldReceive('setPath');
		$backup->shouldReceive('getRestorationFiles')->andReturn(['filepath' => 'filename']);
		$backup->shouldReceive('getWorkingFilepath')->andReturn('123');
		$backup->shouldReceive('restore')->andReturn(true);

		$backupFactory = Mockery::mock('Cornford\Backup\BackupFactory');
		$backupFactory->shouldReceive('build')->andReturn($backup);

		$configInstance = Mockery::mock('Illuminate\Config\Repository');
		$configInstance->shouldReceive('get')->andReturn([]);

		$this->beConstructedWith($backupFactory, $configInstance);
	}

	function it_is_initializable()
	{
		$this->shouldHaveType('Cornford\Backup\Commands\BackupCommandRestore');
	}

	function it_should_get_a_backup_instance()
	{
		$this->getBackupInstance()->shouldHaveType('Cornford\Backup\Contracts\BackupInterface');
	}

	function it_should_execute_when_calling_fire_action(QuestionHelper $question, HelperSet $helpers)
	{
		$helpers->get('question')->willReturn($question);
		$input = new ArrayInput([]);
		$output = new NullOutput;
		$query = Argument::type('Symfony\Component\Console\Question\ChoiceQuestion');
		$question->ask($input, $output, $query)->willReturn(1);
		$helpers->get('question')->willReturn($question);
		$this->setHelperSet($helpers);
		$this->run($input, $output);
		$this->fire();
	}

}
