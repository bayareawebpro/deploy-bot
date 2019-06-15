<?php
return [
    'endpoint' => env('SLACK_ENDPOINT', '#logger'),
    'channel' => env('SLACK_CHANNEL', '#logger'),
    'username' => env('SLACK_USERNAME', 'DbTool'),
    'emoji' => env('SLACK_EMOJI'),
];
