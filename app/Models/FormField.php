<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormField extends Model
{
    use HasFactory;

    public const FIELD_TYPES = [
        'text',
        'number',
        'date',
        'textarea',
        'select',
        'radio',
        'file',
    ];

    protected $fillable = [
        'exam_id',
        'label',
        'name',
        'type',
        'options',
        'is_required',
        'is_repeatable',
        'max_repeat',
        'sort_order',
    ];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
        'is_repeatable' => 'boolean',
        'max_repeat' => 'integer',
    ];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }
}
