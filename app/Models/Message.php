<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Message
 *
 * @property int $id
 * @property int|null $chat_id
 * @property array|null $message_json
 * @property string|null $message_text
 * @property string|null $processor
 * @property int $bot_id
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Message extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'messages';

    public $incrementing = false;

    protected $dates = [
        'deleted_at',
    ];

    protected $guarded = [
        'created_at',
        'updated_at',
    ];

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }
}
