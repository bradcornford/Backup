<?php

namespace spec\Cornford\Backup;

use Cornford\Backup\Contracts\BackupFilesystemInterface;
use PhpSpec\ObjectBehavior;

class BackupFilesystemSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(BackupFilesystemInterface::class);
    }
}
