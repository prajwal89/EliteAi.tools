<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SemanticScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'tool1_id',
        'tool2_id',
        'score',
        'timestamp',
        // Add other fillable fields if needed
    ];
}
