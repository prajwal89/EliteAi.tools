<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SemanticScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'tool1_id', //this should be smaller than tool2_id
        'tool2_id', //this should be greater than tool1_id
        'score',
    ];

    protected $casts = [
        'score' => 'float',
    ];
}
