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
    public const TYPE_TESTIMONIAL = 'testimoni';
    public const TYPE_PROFILE_LOGO = 'profil_logo';
    public const TYPE_PROFILE_NAME = 'profil_nama_sekolah';
    public const TYPE_PROFILE_GREETING = 'profil_kata_sambutan';
    public const TYPE_PROFILE_VISION = 'profil_visi_misi';
    public const TYPE_PROFILE_HISTORY = 'profil_sejarah';
    public const TYPE_PROFILE_PROGRAM = 'profil_program_kegiatan';
    public const TYPE_PROFILE_CONTACT = 'profil_kontak';

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
            self::TYPE_TESTIMONIAL => 'Testimoni',
        ];
    }

    public static function profileTypeOptions(): array
    {
        return [
            self::TYPE_PROFILE_LOGO => 'Logo',
            self::TYPE_PROFILE_NAME => 'Nama Sekolah',
            self::TYPE_PROFILE_GREETING => 'Kata Sambutan',
            self::TYPE_PROFILE_VISION => 'Visi Misi & Strategi Pembelajaran',
            self::TYPE_PROFILE_HISTORY => 'Sejarah',
            self::TYPE_PROFILE_PROGRAM => 'Program Kegiatan RA Fadhilah',
            self::TYPE_PROFILE_CONTACT => 'Kontak Kami',
        ];
    }

    public static function allTypeOptions(): array
    {
        return self::typeOptions() + self::profileTypeOptions();
    }
}
