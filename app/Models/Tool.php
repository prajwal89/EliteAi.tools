<?php

namespace App\Models;

use App\Enums\PricingType;
use App\Enums\SearchAbleTable;
use App\Interfaces\MeilisearchAble;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tool extends Model implements MeilisearchAble
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'tag_line',

        //* content
        'summary',
        'description',
        'top_features',
        'use_cases',
        'vectors',
        'model_type',

        'domain_name',
        'pricing_type',
        'home_page_url',
        'uploaded_screenshot',
        'uploaded_favicon',
        'owner_id',
        'contact_email',

        'instagram_id',
        'tiktok_id',
        'twitter_id',
        'linkedin_id',
        'linkedin_company_id',
        'facebook_id', //profile id
        'youtube_channel_id',
        'telegram_channel_id',
        'subreddit_id',
        'discord_channel_invite_id',

        'android_app_id',
        'ios_app_id',

        'chrome_extension_id',
        'firefox_extension_id',

        //* feature flags
        'has_api',
        // 'has_documentation',

        'yt_introduction_video_id', //introduction video of the tool
    ];

    protected $casts = [
        'has_api' => 'boolean',
        'top_features' => 'json',
        'use_cases' => 'json',
        'pricing_type' => PricingType::class,
        'vectors' => 'json',
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * $paragraphToEmbed will be used to calculate vector embeddings
     * that embeddings can be later used for
     * 1.Searching
     * 2.Recommendation
     */
    public function getParagraphForVectorEmbeddings(): string
    {
        $paragraphToEmbed = '';

        $paragraphToEmbed .= $this->name . PHP_EOL;
        $paragraphToEmbed .= $this->tag_line . PHP_EOL;
        $paragraphToEmbed .= $this->summary . PHP_EOL;
        $paragraphToEmbed .= strip_tags($this->description) . PHP_EOL;

        if (!empty($this->top_features)) {
            $paragraphToEmbed .= PHP_EOL . 'Features' . PHP_EOL;

            foreach ($this->top_features as $feature) {
                $paragraphToEmbed .= $feature . PHP_EOL;
            }
        }

        if (!empty($this->use_cases)) {
            $paragraphToEmbed .= PHP_EOL . 'Use-Cases' . PHP_EOL;

            foreach ($this->use_cases as $useCase) {
                $paragraphToEmbed .= $useCase . PHP_EOL;
            }
        }

        return $paragraphToEmbed;
    }

    /**
     * array that will be sent for indexing on meilisearch
     */
    public static function documentsForSearch(int $documentId = null, int $batchNo = 0): array
    {
        // ? or should i include paragraphToEmbed only
        $query = self::select(
            'id',
            'name',
            'slug',
            'tag_line',
            'summary',
            'description',
            'domain_name',
            'top_features',
            'use_cases',
            'vectors',
        );

        if (!empty($documentId)) {
            $query->where('id', $documentId);
        }

        $query->orderBy('id', 'asc');

        $batchSize = SearchAbleTable::TOOL->getBatchSize();

        $offset = $batchNo * $batchSize;

        return $query
            ->offset($offset)
            ->limit($batchSize)
            ->get()
            ->toArray();
    }

    public static function documentsForSearchTotalBatches(): int
    {
        return ceil(self::count() / SearchAbleTable::TOOL->getBatchSize());
    }
}
