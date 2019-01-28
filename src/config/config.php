<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Enable Backup
	|--------------------------------------------------------------------------
	|
	| Enable database backup.
	|
	*/
	'enabled' => true,

	/*
	|--------------------------------------------------------------------------
	| Path
	|--------------------------------------------------------------------------
	|
	| A database backup path, absolute path, or path relative from public
	| directory, a trailing slash is required.
	|
	*/
	'path' => '../storage/backup/',

	/*
	|--------------------------------------------------------------------------
	| Filename
	|--------------------------------------------------------------------------
	|
	| A database export filename to use when exporting databases.
	|
	*/
	'filename' => 'backup-' . date('Ymd-His'),

	/*
	|--------------------------------------------------------------------------
	| Enable Compression
	|--------------------------------------------------------------------------
	|
	| Enable backup compression using gzip. Requires gzencode/gzdecode.
	|
	*/
	'compress' => true,

	/*
	|--------------------------------------------------------------------------
	| Database Engine Processors
	|--------------------------------------------------------------------------
	|
	| Set the database engines processor location, trailing slash is required.
	|
	*/
	'processors' => array(
		'mysql' => array(
			'export' => env('MYSQLDUMP_PATH', '/usr/bin/'),
			'restore' => env('MYSQLDUMP_PATH', '/usr/bin/')
		),
		'pqsql' => array(
			'export' => env('PSQLDUMP_PATH', '/usr/bin/'),
			'restore' => env('PSQLDUMP_PATH', '/usr/bin/')
		),
		'sqlite' => array(
			'export' => env('SQLITEDUMP_PATH', null),
			'restore' => env('SQLITEDUMP_PATH', null)
		),
		'sqlsrv' => array(
			'export' => env('SQLSRVDUMP_PATH', '/usr/bin/'),
			'restore' => env('SQLSRVDUMP_PATH', '/usr/bin/')
		),
	),

);
