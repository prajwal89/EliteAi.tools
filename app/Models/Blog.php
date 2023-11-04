<?php

namespace App\Models;

use App\Enums\BlogType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// ? should i add category and tags for blog
// ? should i stores vectors and model type
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
}
