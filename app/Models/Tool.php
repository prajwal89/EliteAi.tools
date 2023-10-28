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
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public static function documentsForSearch(int $documentId = null, int $batchNo = 0): array
    {
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
        );

        if (!empty($courseId)) {
            $query->where('id', $courseId);
        }

        $query->orderBy('id', 'asc');

        $batchSize = SearchAbleTable::TOOL->getBatchSize();

        $offset = $batchNo * $batchSize;

        return $query->offset($offset)->limit($batchSize)->get()->toArray();
    }

    public static function documentsForSearchTotalBatches(): int
    {
        return ceil(self::count() / SearchAbleTable::TOOL->getBatchSize());
    }
}
