<?php
return [
    'github' => [
        'webhook_key' => env('GITHUB_WEBHOOK_SECRET', null),
        'branch'    => env('GITHUB_DEPLOY_BRANCH','refs/heads/master'),
    ]
];
