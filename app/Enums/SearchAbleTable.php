<?php

namespace App\Enums;

use App\Models\Tool;
use Exception;
use Illuminate\Database\Eloquent\Model;

enum SearchAbleTable: string
{
    case TOOL = 'tool';  //table name

    /**
     * index name is {prefix}_{tableName}
     *
     * @return string
     */
    public function getIndexName(): string
    {
        return match ($this) {
            SearchAbleTable::TOOL => config('custom.meilisearch.prefix') . '_' . $this->getModelInstance()->getTable(),
            default => throw new Exception("Cannot get index name for table {$this->value}"),
        };
    }

    /**
     * For sending data for indexing
     *
     * @return string
     */
    public function getBatchSize(): string
    {
        return match ($this) {
            SearchAbleTable::TOOL => 5000,
            default => throw new Exception("Cannot get batch size for table {$this->value}"),
        };
    }

    public function getModelInstance(): Model
    {
        return match ($this) {
            SearchAbleTable::TOOL => new Tool(),
            default => throw new Exception("Cannot get model instance for table {$this->value}"),
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
