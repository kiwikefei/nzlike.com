<?php
return [
    'github' => [
        'webhook_secret' => env('GITHUB_WEBHOOK_SECRET', null),
        'branch'    => env('GITHUB_DEPLOY_BRANCH','refs/heads/master'),
    ]
];
