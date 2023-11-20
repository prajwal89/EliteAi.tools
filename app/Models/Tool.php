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
        '_vectors',
        'model_type',

        'domain_name',
        'pricing_type',
        'monthly_subscription_starts_from',
        'pay_once_price_starts_from',
        'home_page_url',
        'uploaded_screenshot',
        'uploaded_favicon',
        'owner_id',
        'contact_email',

        //social handles
        'instagram_id',
        'tiktok_id',
        'twitter_id',
        'linkedin_id',
        'linkedin_company_id',
        'facebook_id', //profile id
        'facebook_page_id', // https://www.facebook.com/PublerNation/
        'dribbble_id',
        'behance_id',
        'pinterest_id',
        'youtube_channel_id',
        'youtube_handle_id',
        'telegram_channel_id',
        'subreddit_id',
        'discord_channel_invite_id',
        'github_repository_path',

        'android_app_id',
        'ios_app_id',

        'chrome_extension_id',
        'firefox_extension_id',
        // https://www.microsoft.com/store/apps/9NNT80B3595S
        'window_store_id',
        // https://apps.apple.com/us/app/podcast-player-podurama/id1497491520?platform=mac
        'mac_store_id',

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
        '_vectors' => 'json',
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
     * ! be carefull to change return value as we have to 
     * ! recalculate vector embeddings of all tools 
     * ! as well as save semantic distance for all tools
     */
    public function getParagraphForVectorEmbeddings(): string
    {
        $paragraphToEmbed = '';

        //! we do not need this 
        // see here  http://clgnotes.esy.es/tool/stockgpt
        // unrelated recommendation including GPT in name in tool

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

    public function getFormattedFeatures(): array
    {
        $formattedArray = [];

        foreach ($this->top_features as $feature) {
            if (str($feature)->contains(':')) {
                $formattedArray[] = '<strong>' . str($feature)->before(':') . ':</strong>' . str($feature)->after(':');
            } else {
                $formattedArray[] = $feature;
            }
        }

        return $formattedArray;
    }

    /**
     * array that will be sent for indexing on meilisearch
     * it will output single document if document id is given
     * else it will give array of bached documents
     */
    public static function documentsForSearch(int $documentId = null, int $batchNo = 0): array
    {
        $batchSize = SearchAbleTable::TOOL->getBatchSize();

        // ? or should i include paragraphToEmbed only
        $query = self::with([
            'tags',
            'categories',
        ])->select(
            'id',
            'name',
            'slug',
            'tag_line',
            'summary',
            'description',
            'domain_name',
            'top_features',
            'use_cases',
            'pricing_type',
            'monthly_subscription_starts_from',
            'pay_once_price_starts_from',
            'github_repository_path',
            'android_app_id',
            'ios_app_id',
            'chrome_extension_id',
            'firefox_extension_id',
            'window_store_id',
            'mac_store_id',
            'has_api',
            '_vectors',
            'model_type',
        );

        if (!empty($documentId)) {
            $query->where('id', $documentId);
        }

        $query->orderBy('id', 'asc');

        return $query->offset($batchNo * $batchSize)
            ->limit($batchSize)
            ->get()
            ->map(function ($model) {
                $model->tags = $model->tags->pluck('name')->toArray();
                $model->categories = $model->categories->pluck('name')->toArray();

                return $model->withoutRelations();
            })
            ->toArray();
    }

    public static function documentsForSearchTotalBatches(): int
    {
        return ceil(self::count() / SearchAbleTable::TOOL->getBatchSize());
    }
}
