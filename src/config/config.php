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
	'path' => '../app/storage/backup/',

	/*
	|--------------------------------------------------------------------------
	| Filename
	|--------------------------------------------------------------------------
	|
	| A database export filename.
	|
	*/
	'filename' => 'backup-' . date('Ymd-His'),

	/*
	|--------------------------------------------------------------------------
	| Enable Compression
	|--------------------------------------------------------------------------
	|
	| Enable backup compression using gzip.
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
			'export' => '/usr/bin/',
			'restore' => '/usr/bin/'
		),
		'pqsql' => array(
			'export' => '/usr/bin/',
			'restore' => '/usr/bin/'
		),
		'sqlite' => array(
			'export' => null,
			'restore' => null
		),
		'sqlsrv' => array(
			'export' => '/usr/bin/',
			'restore' => '/usr/bin/'
		),
	),

);