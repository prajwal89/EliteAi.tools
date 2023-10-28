<?php

return [
    'load_google_analytic_tag' => false,
    'load_microsoft_clarity_analytic_tag' => false,
    'admin_panel_base_url' => 'admin-area',
    'admin_email' => 'example@mail.com',
    'meilisearch' => [
        'prefix' => 'ai_tools_repo_',
        'host' => env('MEILISEARCH_HOST', 'http://localhost:7700'),
        'key' => env('MEILISEARCH_KEY', null),
    ],
];
