<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
    use HasFactory, SoftDeletes;

    protected $table = 'bots';

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
