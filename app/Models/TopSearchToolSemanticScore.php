<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopSearchToolSemanticScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'top_search_id',
        'tool_id',
        'score',
        'model_type',
    ];

    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }
}
