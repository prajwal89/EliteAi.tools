<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopSearch extends Model
{
    use HasFactory;

    protected $table = 'top_searches';

    protected $fillable = [
        'query',
        'slug',
        'extracted_from_tool_id',
        'search_id',
        '_vectors',
        'model_type',
    ];

    protected $casts = [
        '_vectors' => 'json',
    ];

    public function search()
    {
        return $this->belongsTo(Search::class);
    }

    public function tools()
    {
        return $this->hasMany(Tool::class);
    }

    public function extractedFromTool()
    {
        return $this->belongsTo(Tool::class, 'extracted_from_tool_id');
    }

    public function topSearchToolSemanticScores()
    {
        return $this->hasMany(TopSearchToolSemanticScore::class);
    }

    public function getParagraphForVectorEmbeddings(): string
    {
        return $this->query;
    }
}
