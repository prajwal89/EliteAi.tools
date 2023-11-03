<?php

namespace App\Enums;

enum BlogType: string
{
        //based on semantic scores of the article title description
    case SEMANTIC_SCORE = 'Semantic Score';
        // find blade view in /blogs/{slug}.blade.php
    case BLADE_VIEW = 'Blade View';
        // get content from content column in articles table
    case HTML = 'HTML';
    case USER_SELECTED_TOOLS = 'User selected tools';
}
