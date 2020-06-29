<?php

namespace Cornford\Backup\Facades;

use Illuminate\Support\Facades\Facade;

class BackupFacade extends Facade
{
    /**
     * Get facade accessor.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'backup';
    }
}
