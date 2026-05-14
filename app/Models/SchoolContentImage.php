<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolContentImage extends Model
{
    protected $fillable = [
        'school_content_id',
        'image_path',
        'sort_order',
    ];

    public function content(): BelongsTo
    {
        return $this->belongsTo(SchoolContent::class, 'school_content_id');
    }
}
