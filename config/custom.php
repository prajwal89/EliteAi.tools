<?php

return [
    'load_google_analytic_tag' => false,
    'load_microsoft_clarity_analytic_tag' => false,
    'admin_panel_base_url' => 'admin-area',
    'admin_email' => 'admin@eliteai.tools',

    // ! we need to change all embeddings if we are going to change this key
    'current_embedding_model' => \App\Enums\ModelType::OPEN_AI_ADA_002,

    // page qualification requirements
    'tag_page' => [
        'minimum_tools_required' => 4,
    ],
    'blog_page' => [
        'minimum_tools_required' => 4,
        'minimum_semantic_score' => 0.850,
    ],
    'popular_search' => [
        'minimum_tools_required' => 4,
        'minimum_semantic_score' => 0.850,
    ],
    'alternative_tools' => [
        'minimum_tools_required' => 4,
        'minimum_semantic_score' => 0.850,
    ],
];
