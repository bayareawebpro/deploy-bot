<?php
return [
    'staging' => [
        'url' => env('ENVOYER_PRODUCTION_URL'),
        'project' => env('ENVOYER_PRODUCTION_ID'),
    ],
    'production' => [
        'url' => env('ENVOYER_STAGING_URL'),
        'project' => env('ENVOYER_STAGING_ID'),
    ],
];
