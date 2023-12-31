<?php

namespace App\Models;

use App\Enums\BlogType;
use App\Enums\SearchAbleTable;
use App\Interfaces\MeilisearchAble;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// ? should i add category and tags for blog
// ? should i stores vectors and model type
class Blog extends Model implements MeilisearchAble
{
    use HasFactory;

    protected $fillable = [
        'blog_type',
        'title',
        'serp_title',
        'slug',
        'description',
        'content',
        'min_semantic_score',
        'user_id',
        '_vectors',
        'model_type',
    ];

    protected $casts = [
        'blog_type' => BlogType::class,
        '_vectors' => 'json',
    ];

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function toolSemanticScores()
    {
        return $this->hasMany(BlogToolSemanticScore::class);
    }

    /**
     * $paragraphToEmbed will be used to calculate vector embeddings
     * that embeddings can be later used for
     * 1.Searching
     * 2.Recommendation
     * 3.Fetching tools for this blog
     */
    public function getParagraphForVectorEmbeddings(): string
    {
        $paragraphToEmbed = '';

        $paragraphToEmbed .= $this->title . PHP_EOL;
        $paragraphToEmbed .= $this->description . PHP_EOL;

        return $paragraphToEmbed;
    }

    /**
     * array that will be sent for indexing on meilisearch
     * it will output single document if document id is given
     * else it will give array of bached documents
     */
    public static function documentsForSearch(?int $documentId = null, int $batchNo = 0): array
    {
        $batchSize = SearchAbleTable::BLOG->getBatchSize();

        // ? or should i include paragraphToEmbed only
        $query = self::select(
            'id',
            'title',
            'description',
        );

        if (!empty($documentId)) {
            $query->where('id', $documentId);
        }

        $query->orderBy('id', 'asc');

        return $query->offset($batchNo * $batchSize)
            ->limit($batchSize)
            ->get()
            ->toArray();
    }

    public static function documentsForSearchTotalBatches(): int
    {
        return ceil(self::count() / SearchAbleTable::BLOG->getBatchSize());
    }
}
