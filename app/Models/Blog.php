<?php

namespace App\Models;

use App\Enums\BlogType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_type',
        'title',
        'slug',
        'description',
        'content',
        'user_id',
    ];

    protected $casts = [
        'blog_type' => BlogType::class,
    ];

    public function author()
    {
        return $this->belongsTo(User::class);
    }
}
