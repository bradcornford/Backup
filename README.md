# An easy way backup and restore databases in Laravel.

[![Latest Stable Version](https://poser.pugx.org/cornford/backup/version.png)](https://packagist.org/packages/cornford/backup)
[![Total Downloads](https://poser.pugx.org/cornford/backup/d/total.png)](https://packagist.org/packages/cornford/backup)
[![Build Status](https://travis-ci.org/bradcornford/Backup.svg?branch=master)](https://travis-ci.org/bradcornford/backup)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/bradcornford/Backup/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/bradcornford/Backup/?branch=master)

### For Laravel 4.x, check [version 1.0.0](https://github.com/bradcornford/Backup/tree/v1.0.0)

Think of Backup as an easy way to backup and restore a database, with command line integration to Laravel's artisan. These include:

- `Backup::export`
- `Backup::restore`
- `Backup::setBackupEngineInstance`
- `Backup::getBackupEngineInstance`
- `Backup::setBackupFilesystemInstance`
- `Backup::getBackupFilesystemInstance`
- `Backup::setEnabled`
- `Backup::getEnabled`
- `Backup::setPath`
- `Backup::getPath`
- `Backup::setCompress`
- `Backup::getCompress`
- `Backup::setFilename`
- `Backup::getFilename`
- `Backup::getWorkingFilepath`
- `Backup::getRestorationFiles`
- `Backup::getProcessOutput`

## Installation

Begin by installing this package through Composer. Edit your project's `composer.json` file to require `cornford/backup`.

	"require": {
		"cornford/backup": "2.*"
	}

Next, update Composer from the Terminal:

	composer update

Once this operation completes, the next step is to add the service provider. Open `config/app.php`, and add a new item to the providers array.

	'Cornford\Backup\Providers\BackupServiceProvider',

The next step is to introduce the facade. Open `config/app.php`, and add a new item to the aliases array.

	'Backup'         => 'Cornford\Backup\Facades\Backup',

Finally we need to introduce the configuration files into your application.

	php artisan vendor:publish --provider="Cornford\\Backup\\Providers\\BackupServiceProvider"

That's it! You're all set to go.

## Configuration

You can now configure Backup in a few simple steps. Open `config/backup.php` and update the options as needed.

- `enabled` - Enable Backup.
- `path` - A database backup path, absolute path, or path relative from public directory, a trailing slash is required.
- `filename` - A database export filename to use when exporting databases.
- `compress` - Enable backup compression using gzip. Requires gzencode/gzdecode.
- `processors` - Set the database engines processor location, trailing slash is required.

## Usage

It's really as simple as using the Backup class in any Controller / Model / File you see fit with:

`Backup::`

This will give you access to

- [Export](#export)
- [Restore](#Restore)
- [Set Backup Engine Instance](#set-backup-engine-instance)
- [Get Backup Engine Instance](#get-backup-engine-instance)
- [Set Backup Filesystem Instance](#set-backup-filesystem-instance)
- [Get Backup Filesystem Instance](#get-backup-filesystem-instance)
- [Set Enabled](#set-enabled)
- [Get Enabled](#get-enabled)
- [Set Path](#set-path)
- [Get Path](#get-path)
- [Set Compress](#set-compress)
- [Get Compress](#get-compress)
- [Set Filename](#set-filename)
- [Get Filename](#get-filename)
- [Get Working Filepath](#get-working-filepath)
- [Get Restoration Files](#get-restoration-files)
- [Get Process Output](#get-process-output)

### Export

The `export` method allows a database export file to be created in the defined backup location, with an optional filename option.

	Backup::export();
	Backup::export('database_backup');

### Restore

The `restore` method allows a database export file to be restored to the database, specifying a full filepath to the file.

	Backup::restore('./database_backup.sql');

### Set Backup Engine Instance

The `setBackupEngineInstance` method allows a custom backup engine instance object to be utilised, implementing the BackupEngineInterface.

	Backup::setBackupEngineInstance(new BackupEngineMysql(new BackupProcess(new Symfony\Component\Process\Process), 'database', 'localhost', 3306, 'root', '', []));

### Get Backup Engine Instance

The `getBackupEngineInstance` method returns the current backup engine instance object.

	Backup::getBackupEngineInstance();

### Set Backup Filesystem Instance

The `setBackupFilesystemInstance` method allows a custom backup filesystem instance object to be utilised, implementing the BackupFilesystemInterface.

	Backup::setBackupFilesystemInstance(new BackupFilesystemInstance);

### Get Backup Filesystem Instance

The `getBackupFilesystemInstance` method returns the current backup filesystem instance object.

	Backup::getBackupFilesystemInstance();

### Set Enabled

The `setEnabled` method allows backup to be switched on or off, specifying a boolean for state.

	Backup::setEnabled(true);
	Backup::setEnabled(false);

### Get Enabled

The `getEnabled` method returns the current backup enabled status, returning a boolean for its state.

	Backup::getEnabled();

### Set Path

The `setPath` method allows backup location path to be set, specifying a relative or absolute path as a string, a trailing slash is required.

	Backup::setPath('/path/to/directory/');

### Get Path

The `getPath` method returns the current absolute backup path in string format.

	Backup::getPath();

### Set Compress

The `setCompress` method allows backup file compression to be switched on or off, specifying a boolean for state.

	Backup::setCompress(true);
	Backup::setCompress(false);

### Get Compress

The `getCompress` method returns the current compression backup status, returning a boolean for its state.

	Backup::getCompress();

### Set Filename

The `setFilename` method allows backup filename to be set, specified in a string format.

	Backup::setFilename('database_backup');
	Backup::setFilename('backup-' . date('Ymd-His'));

### Get Filename

The `getFilename` method returns the current set backup filename in a string format.

	Backup::getFilename();

### Get Working Filepath

The `getWorkingFilepath` method returns the current set working filepath of the current item being processed in a string format.

	Backup::getWorkingFilepath();

### Get Restoration Files

The `getRestorationFiles` method returns an array containing all of the restoration file filepaths within a give path, an optional absolute path can be set as a string.

	Backup::getRestorationFiles();
	Backup::getRestorationFiles('/path/to/directory/');

### License

Backup is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
