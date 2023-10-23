<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tool extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'tag_name',
        'summary',
        'domain_name',
        'home_page_url',
        'has_api',
        'top_features',
        'use_cases',
        'uploaded_screenshot',
        'uploaded_favicon',
        'owner_id',
    ];

    protected $casts = [
        'has_api' => 'boolean',
        'top_features' => 'json',
        'use_cases' => 'json',
    ];

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }
}
