<?php
return [
    'staging' => [
        'url' => env('ENVOYER_STAGING_URL'),
        'project' => env('ENVOYER_STAGING_ID'),
        'database' => env('ENVOYER_STAGING_DATABASE'),
    ],
    'production' => [
        'url' => env('ENVOYER_PRODUCTION_URL'),
        'project' => env('ENVOYER_PRODUCTION_ID'),
        'database' => env('ENVOYER_PRODUCTION_DATABASE'),
    ],
];
