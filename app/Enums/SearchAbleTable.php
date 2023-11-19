<?php

namespace App\Enums;

use App\Models\Tool;
use Exception;
use Illuminate\Database\Eloquent\Model;

enum SearchAbleTable: string
{
    case TOOL = 'tools';  //table name

    /**
     * index name is {prefix}_{tableName}
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

    /**
     * fallback if search meilisearch fails
     * we need to have fulltext index on these columns
     * e.g $table->fullText(['name', 'summary', 'description'])
     */
    public function searchAbleColumns(): array
    {
        return match ($this) {
            SearchAbleTable::TOOL => ['name', 'summary', 'description'],
            default => throw new Exception("Searchable columns are not set for table {$this->value}"),
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Undocumented function
     *  @see https://www.meilisearch.com/docs/reference/api/settings
     * @return array
     */
    public function meilisearchIndexSettings()
    {
        return match ($this) {
            SearchAbleTable::TOOL => [
                'displayedAttributes' => ['*'],
                'searchableAttributes' => ['*'],
                'filterableAttributes' => [],
                'sortableAttributes' => [],
                'rankingRules' => [
                    'words',
                    'typo',
                    'proximity',
                    'attribute',
                    'sort',
                    'exactness',
                ],
                'stopWords' => [
                    // common
                    'a', 'an', 'and', 'are', 'as', 'at', 'be', 'but', 'by', 'for',
                    'if', 'in', 'into', 'is', 'it', 'no', 'not', 'of', 'on',
                    'or', 'such', 'that', 'the', 'their', 'then', 'there', 'these',
                    'they', 'this', 'to', 'was', 'will', 'with',
                ],
                "nonSeparatorTokens" => [],
                "separatorTokens" => [],
                "dictionary" => [],
                'synonyms' => [],
                'distinctAttribute' => null,
                'typoTolerance' => [
                    'enabled' => true,
                    'minWordSizeForTypos' => [
                        'oneTypo' => 5,
                        'twoTypos' => 9,
                    ],
                    'disableOnWords' => [],
                    'disableOnAttributes' => [],
                ],
                'faceting' => [
                    'maxValuesPerFacet' => 100,
                ],
                'pagination' => [
                    'maxTotalHits' => 1000,
                ],
            ],
            default => throw new Exception("Cannot get Settings array for table {$this->value}"),
        };
    }
}
