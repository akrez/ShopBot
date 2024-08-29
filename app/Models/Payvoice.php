<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payvoice extends Model
{
    use HasFactory;

    protected $table = 'payvoices';

    protected $casts = [];

    protected $guarded = [
        'id',
    ];

    public function blog(): BelongsTo
    {
        return $this->belongsTo(Blog::class);
    }
    
    public function scopeOrderDefault(Builder $query)
    {
        $query = $query
            ->orderBy('created_at', 'DESC');
    }
}
