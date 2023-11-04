<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'serp_title',
        'slug',
        'description',
    ];

    public function tools(): BelongsToMany
    {
        return $this->BelongsToMany(Tool::class);
    }
}
