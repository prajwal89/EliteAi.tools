<?php

namespace App\Enums;

use App\Models\Blog;
use App\Models\Tool;
use App\Services\MeilisearchService;
use Exception;
use Illuminate\Database\Eloquent\Model;

enum SearchAbleTable: string
{
    case TOOL = 'tools'; //table name
    case BLOG = 'blogs';

    /**
     * index name is {prefix}_{tableName}
     */
    public function getIndexName(): string
    {
        return config('services.meilisearch.prefix') . '_' . $this->getModelInstance()->getTable();
    }

    /**
     * For sending data for indexing
     */
    public function getBatchSize(): string
    {
        return match ($this) {
            SearchAbleTable::TOOL => 5000,
            SearchAbleTable::BLOG => 5000,
            default => throw new Exception("Cannot get batch size for table {$this->value}"),
        };
    }

    public function getModelInstance(): Model
    {
        return match ($this) {
            SearchAbleTable::TOOL => new Tool(),
            SearchAbleTable::BLOG => new Blog(),
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
            SearchAbleTable::TOOL => [
                'name',
                'summary',
                'description',
            ],
            SearchAbleTable::BLOG => [
                'title',
                'description',
            ],
            default => throw new Exception("Searchable columns are not set for table {$this->value}"),
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Undocumented function
     *
     *  @see https://www.meilisearch.com/docs/reference/api/settings
     *
     * @return array
     */
    public function meilisearchIndexSettings()
    {
        return match ($this) {
            SearchAbleTable::TOOL => array_merge(MeilisearchService::defaultIndexSettings(), [
                //overwrite settings
                'filterableAttributes' => [
                    'monthly_subscription_starts_from',
                    'pay_once_price_starts_from',
                    'pricing_type',
                ],
                'sortableAttributes' => [
                    'monthly_subscription_starts_from',
                    'pay_once_price_starts_from',
                ],
            ]),
            SearchAbleTable::BLOG => array_merge(MeilisearchService::defaultIndexSettings(), [
                //overwrite settings
            ]),
            default => throw new Exception("Cannot get Settings array for table {$this->value}"),
        };
    }
}
