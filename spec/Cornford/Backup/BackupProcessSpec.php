<?php namespace spec\Cornford\Backup;

use Cornford\Backup\Contracts\BackupProcessInterface;
use Cornford\Backup\Exceptions\BackupException;
use Cornford\Backup\Exceptions\BackupExportException;
use Cornford\Backup\Exceptions\BackupRestoreException;
use PhpSpec\ObjectBehavior;
use Mockery;
use Symfony\Component\Process\Process;

class BackupProcessSpec extends ObjectBehavior {

	const command = 'ls -la';
	const output = 'this is some output';

	function let()
	{
		$process = Mockery::mock(Process::class);
		$process->shouldReceive('run');
		$process->shouldReceive('isSuccessful')->andReturn(true);
		$process->shouldReceive('setCommandLine')->andReturn($process);
		$process->shouldReceive('setTimeout')->andReturn($process);
		$process->shouldReceive('stop')->andReturn(1);
		$process->shouldReceive('getErrorOutput')->andReturn(self::output);

		$this->beConstructedWith($process);
	}

	function it_is_initializable()
	{
		$this->shouldHaveType(BackupProcessInterface::class);
	}

	function it_should_set_a_process_instance()
	{
		$process = Mockery::mock(Process::class);
		$process->shouldReceive('run');
		$process->shouldReceive('isSuccessful')->andReturn(true);
		$process->shouldReceive('setCommandLine')->andReturn($process);
		$process->shouldReceive('setTimeout')->andReturn($process);
		$process->shouldReceive('stop')->andReturn(1);
		$process->shouldReceive('getErrorOutput')->andReturn(self::output);

		$this->setProcessInstance($process);
		$this->getProcessInstance()->shouldReturn($process);
	}

	function it_should_set_a_process_timeout()
	{
		$this->setProcessTimeout(1);
		$this->getProcessTimeout()->shouldReturn(1);
	}

	function it_should_run_a_command()
	{
		$this->run(self::command)->shouldReturn(true);
	}

	function it_should_run_a_command_with_a_set_process_instance()
	{
		$process = Mockery::mock(Process::class);
		$process->shouldReceive('run');
		$process->shouldReceive('isSuccessful')->andReturn(true);
		$process->shouldReceive('setCommandLine')->andReturn($process);
		$process->shouldReceive('setTimeout')->andReturn($process);
		$process->shouldReceive('stop')->andReturn(1);
		$process->shouldReceive('getErrorOutput')->andReturn(self::output);

		$this->setProcessInstance($process);
		$this->run(self::command)->shouldReturn(true);
	}

	function it_should_run_a_command_with_a_set_process_timeout()
	{
		$this->setProcessTimeout(1);
		$this->run(self::command)->shouldReturn(true);
	}

	function it_should_run_a_command_with_a_set_process_instance_and_a_set_process_timeout()
	{
		$process = Mockery::mock(Process::class);
		$process->shouldReceive('run');
		$process->shouldReceive('isSuccessful')->andReturn(true);
		$process->shouldReceive('setCommandLine')->andReturn($process);
		$process->shouldReceive('setTimeout')->andReturn($process);
		$process->shouldReceive('stop')->andReturn(1);
		$process->shouldReceive('getErrorOutput')->andReturn(self::output);

		$this->setProcessInstance($process);
		$this->setProcessTimeout(1);
		$this->run(self::command)->shouldReturn(true);
	}

	function it_should_fail_to_run_a_command_without_exception()
	{
		$process = Mockery::mock(Process::class);
		$process->shouldReceive('run');
		$process->shouldReceive('isSuccessful')->andReturn(false);
		$process->shouldReceive('setCommandLine')->andReturn($process);
		$process->shouldReceive('setTimeout')->andReturn($process);
		$process->shouldReceive('stop')->andReturn(1);
		$process->shouldReceive('getErrorOutput')->andReturn(self::output);

		$this->setProcessInstance($process);
		$this->run(self::command)->shouldReturn(false);
	}

	function it_should_fail_to_run_a_command_without_exception_and_have_command_output()
	{
		$process = Mockery::mock(Process::class);
		$process->shouldReceive('run');
		$process->shouldReceive('isSuccessful')->andReturn(false);
		$process->shouldReceive('setCommandLine')->andReturn($process);
		$process->shouldReceive('setTimeout')->andReturn($process);
		$process->shouldReceive('stop')->andReturn(1);
		$process->shouldReceive('getErrorOutput')->andReturn(self::output);

		$this->setProcessInstance($process);
		$this->run(self::command)->shouldReturn(false);
		$this->getOutput()->shouldReturn(self::output);
	}

	function it_should_throw_an_exception_when_a_process_throws_an_exception_for_a_default_operation()
	{
		$process = Mockery::mock(Process::class);
		$process->shouldReceive('run')->andThrow('Exception', 'Exception');
		$process->shouldReceive('stop')->andReturn(0);

		$this->setProcessInstance($process);
		$this->shouldThrow(BackupException::class)->during('run', [self::command]);
	}

	function it_should_throw_an_exception_when_a_process_throws_an_exception_for_an_export_operation()
	{
		$process = Mockery::mock(Process::class);
		$process->shouldReceive('run')->andThrow('Exception', 'Exception');
		$process->shouldReceive('stop')->andReturn(0);

		$this->setProcessInstance($process);
		$this->shouldThrow(BackupExportException::class)->during('run', [self::command, 'export']);
	}

	function it_should_throw_an_exception_when_a_process_throws_an_exception_for_an_restore_operation()
	{
		$process = Mockery::mock(Process::class);
		$process->shouldReceive('run')->andThrow('Exception', 'Exception');
		$process->shouldReceive('stop')->andReturn(0);

		$this->setProcessInstance($process);
		$this->shouldThrow(BackupRestoreException::class)->during('run', [self::command, 'restore']);
	}

}
