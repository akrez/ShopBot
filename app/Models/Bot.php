<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Bot
 *
 * @property int $id
 * @property string $token
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Bot extends Model
{
    use HasFactory;

    protected $table = 'bots';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'token',
    ];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function blog(): BelongsTo
    {
        return $this->belongsTo(Blog::class);
    }

    public function scopeOrderDefault(Builder $query)
    {
        $query = $query
            ->orderBy('updated_at', 'DESC')
            ->orderBy('created_at', 'DESC');
    }
}
