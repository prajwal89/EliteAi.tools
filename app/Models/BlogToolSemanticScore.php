<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogToolSemanticScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_id',
        'tool_id',
        'score',
        'model_type',
    ];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }
}
