<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
        ],

        'admin' => [
            'driver'     => 'local',
            'root'       => public_path('uploads'),
            'visibility' => 'public',
            'url' => env('APP_URL').'/uploads/',
        ],

        'oss' => [
            'driver' => 'oss',
            'access_id' => env('OSS_ACCESS_ID'),// 这里是你的 OSS 的 accessId,
            'access_key' =>env('OSS_ACCESS_KEY'),// 这里是你的 OSS 的 accessKey,
            'bucket' => env('OSS_BUCKET'),// 这里是你的 OSS 自定义的存储空间名称,
            'endpoint' => env('OSS_ENDPOINT'), // 这里以杭州为例
            'cdnDomain' => '', // 使用 cdn 时才需要写, https://加上 Bucket 域名
            'ssl' => true, // true 使用 'https://' false 使用 'http://'. 默认 false,
            'isCName' => false, // 是否使用自定义域名，true: Storage.url() 会使用自定义的 cdn 或域名生成文件 url，false: 使用外部节点生成url
            'debug' => false,
        ],



    ],

];
