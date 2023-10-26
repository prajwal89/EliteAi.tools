<?php

namespace App\Models;

use App\Enums\PricingType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tool extends Model
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
}
