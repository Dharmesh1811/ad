<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'exam_id',
        'form_data',
        'submitted_at',
        'status',
        'full_name',
        'dob',
        'gender',
        'mobile',
        'email',
        'address',
        'photo',
        'signature',
    ];

    protected $casts = [
        'form_data' => 'array',
        'submitted_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public static function fileUrl(?string $path): ?string
    {
        if (! filled($path)) {
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
