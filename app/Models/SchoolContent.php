<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolContent extends Model
{
    public const TYPE_INFORMATION = 'informasi';
    public const TYPE_ACHIEVEMENT = 'prestasi';
    public const TYPE_GALLERY = 'galeri';
    public const TYPE_FACILITY = 'fasilitas';
    public const TYPE_ACTIVITY = 'kegiatan';
    public const TYPE_TEACHER = 'tenaga_pendidik';

    protected $fillable = [
        'type',
        'title',
        'excerpt',
        'content',
        'image_path',
        'published_at',
        'is_published',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'date',
            'is_published' => 'boolean',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function images(): HasMany
    {
        return $this->hasMany(SchoolContentImage::class)
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public static function typeOptions(): array
    {
        return [
            self::TYPE_INFORMATION => 'Berita',
            self::TYPE_ACHIEVEMENT => 'Prestasi',
            self::TYPE_GALLERY => 'Galeri',
            self::TYPE_FACILITY => 'Fasilitas',
            self::TYPE_ACTIVITY => 'Kegiatan',
            self::TYPE_TEACHER => 'Tenaga Pendidik',
        ];
    }
}
