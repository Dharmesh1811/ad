<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category',
        'detail_label',
        'detail_value',
        'fee',
        'last_date',
        'status',
    ];

    protected $casts = [
        'last_date' => 'date',
        'fee' => 'decimal:2',
    ];

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function formFields(): HasMany
    {
        return $this->hasMany(FormField::class)->orderBy('sort_order')->orderBy('id');
    }

    public function isOpen(): bool
    {
        return $this->status === 'active' && now()->startOfDay()->lte($this->last_date);
    }
}
