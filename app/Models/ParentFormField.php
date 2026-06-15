<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ParentFormField extends Model
{
    public const TYPES = [
        'text' => 'Teks Singkat',
        'textarea' => 'Teks Panjang',
        'number' => 'Angka',
        'date' => 'Tanggal',
        'select' => 'Pilihan',
    ];

    public const SECTIONS = [
        'child' => 'Data Anak',
        'parent' => 'Data Orang Tua / Wali',
    ];

    protected $fillable = [
        'label',
        'field_key',
        'type',
        'section',
        'placeholder',
        'help_text',
        'options',
        'is_required',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'options' => 'array',
            'is_required' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public static function makeKey(string $label): string
    {
        $base = Str::snake(Str::ascii($label)) ?: 'field';
        $key = $base;
        $counter = 2;

        while (static::where('field_key', $key)->exists()) {
            $key = $base . '_' . $counter++;
        }

        return $key;
    }
}
