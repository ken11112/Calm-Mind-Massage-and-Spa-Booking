<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application for file storage.
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Below you may configure as many filesystem disks as necessary, and you
    | may even configure multiple disks for the same driver. Examples for
    | most supported storage drivers are configured here for reference.
    |
    | Supported drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
            'report' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],

        's3' => [
            'driver' => 's3',
            // Prefer the new STORAGE_AWS_* env vars (to avoid platform reserved AWS_* names),
            // but fall back to the standard AWS_* variables if present.
            'key' => env('STORAGE_AWS_KEY', env('AWS_ACCESS_KEY_ID')),
            'secret' => env('STORAGE_AWS_SECRET', env('AWS_SECRET_ACCESS_KEY')),
            'region' => env('STORAGE_AWS_REGION', env('AWS_DEFAULT_REGION')),
            'bucket' => env('STORAGE_AWS_BUCKET', env('AWS_BUCKET')),
            'url' => env('STORAGE_AWS_URL', env('AWS_URL')),
            'endpoint' => env('STORAGE_AWS_ENDPOINT', env('AWS_ENDPOINT')),
            'use_path_style_endpoint' => env('STORAGE_AWS_USE_PATH_STYLE', env('AWS_USE_PATH_STYLE_ENDPOINT', false)),
            'throw' => false,
            'report' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
