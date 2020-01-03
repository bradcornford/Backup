<?php namespace Cornford\Backup\Facades;

use Illuminate\Support\Facades\Facade;

class BackupFacade extends Facade {

	protected static function getFacadeAccessor() { return 'backup'; }

}
