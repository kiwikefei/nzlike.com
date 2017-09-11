<?php
return [
    'github' => [
        'webhook_key' => env('GITHUB_WEBHOOK_SECRET', null),
        'branch'    => 'refs/heads/master',
    ]
];
