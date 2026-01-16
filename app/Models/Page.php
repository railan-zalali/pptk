<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'subtitle',
        'content_html',
        'hero_photo_path',
        'overview_html',
        'sejarah_html',
        'tujuan_html',
        'manfaat_html',
        'lokasi_html',
        'meta',
        'files',
    ];

    protected $casts = [
        'meta' => 'array',
        'files' => 'array',
    ];
}
