<?php

namespace App\Enums;

use App\Models\Tool;
use Illuminate\Database\Eloquent\Model;

enum SearchAbleTable: string
{
    case TOOL = 'tool';

    public function getIndexName(): string
    {
        return match ($this) {
            SearchAbleTable::TOOL => config('custom.meilisearch.prefix') . '_tools',
        };
    }

    public function getBatchSize(): string
    {
        return match ($this) {
            SearchAbleTable::TOOL => 5000,
        };
    }

    public function getModelInstance(): Model
    {
        return match ($this) {
            SearchAbleTable::TOOL => new Tool(),
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
