<?php

namespace App\Enums;

enum ModelType: string
{
    case All_MINI_LM_L6_V2 = 'sentence-transformers/all-MiniLM-L6-v2';
    case OPEN_AI_ADA_002 = 'text-embedding-ada-002';

    public function totalVectorDimensions(): int
    {
        return match ($this) {
            self::All_MINI_LM_L6_V2 => 384,
            self::OPEN_AI_ADA_002 => 1536,
        };
    }
}
