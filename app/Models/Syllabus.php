<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Syllabus extends Model
{
    use HasFactory;

    protected $table = 'syllabi';

    protected $fillable = [
        'subject_name',
        'file_path',
        'notes',
    ];

    public function getFileUrlAttribute(): ?string
    {
        $path = $this->file_path;
        if (! $path) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '//')) {
            return $path;
        }

        if (str_starts_with($path, 'uploads/')) {
            if (file_exists(public_path($path))) {
                return asset($path);
            }

            if (Storage::disk('public')->exists($path)) {
                return route('uploads.show', ['path' => $path]);
            }
        }

        return asset($path);
    }
}
