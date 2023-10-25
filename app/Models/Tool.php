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
        'summary',
        'description',
        'domain_name',
        'pricing_type',
        'home_page_url',
        'has_api',
        'top_features',
        'use_cases',
        'uploaded_screenshot',
        'uploaded_favicon',
        'owner_id',
        'contact_email',
        'instagram_id',
        'tiktok_id',
        'twitter_id',
        'linkedin_id',
        'linkedin_company_id',
        'android_app_id',
        'ios_app_id',
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
