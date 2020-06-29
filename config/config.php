<?php

return [

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
    'processors' => [
        'mysql' => [
            'export' => env('MYSQLDUMP_PATH', '/usr/bin/'),
            'restore' => env('MYSQLDUMP_PATH', '/usr/bin/')
        ],
        'pqsql' => [
            'export' => env('PSQLDUMP_PATH', '/usr/bin/'),
            'restore' => env('PSQLDUMP_PATH', '/usr/bin/')
        ],
        'sqlite' => [
            'export' => env('SQLITEDUMP_PATH', null),
            'restore' => env('SQLITEDUMP_PATH', null)
        ],
        'sqlsrv' => [
            'export' => env('SQLSRVDUMP_PATH', '/usr/bin/'),
            'restore' => env('SQLSRVDUMP_PATH', '/usr/bin/')
        ],
    ],

];
