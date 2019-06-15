<?php
return [
    'default' => 'staging',
    'disks' => [
        'production' => [
            'driver' => 'local',
            'root' => env('SNAPSHOTS_PATH').DIRECTORY_SEPARATOR.'production',
        ],
        'staging' => [
            'driver' => 'local',
            'root' => env('SNAPSHOTS_PATH').DIRECTORY_SEPARATOR.'staging',
        ],
    ]
];
