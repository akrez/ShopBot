<?php

namespace App\Models;

use App\Enums\Contact\ContactType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contact extends Model
{
    use HasFactory;

    protected $table = 'contacts';

    protected $casts = [
        'contact_type' => ContactType::class,
    ];

    protected $fillable = [
        'contact_type',
        'contact_key',
        'contact_value',
        'contact_link',
        'contact_order',
    ];

    public function blog(): BelongsTo
    {
        return $this->belongsTo(Blog::class);
    }

    public function scopeOrderDefault(Builder $query)
    {
        $query = $query
            ->orderBy('contact_order', 'DESC')
            ->orderBy('created_at', 'ASC');
    }
}
