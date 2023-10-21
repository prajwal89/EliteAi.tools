<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtractedToolDomain extends Model
{
    use HasFactory;

    protected $fillable = [
        'domain_name',
        'home_page_url',
        'process_status',
        'process_error',
        'created_at',
        'updated_at',
    ];
}
