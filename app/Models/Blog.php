<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Blog
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $short_description
 * @property string|null $description
 * @property int $created_by
 * @property string|null $blog_status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property User $user
 * @property Collection|Product[] $products
 * @property Collection|Tag[] $tags
 * @property Collection|User[] $users
 */
class Blog extends Model
{
    protected $table = 'blogs';

    protected $casts = [
        'created_by' => 'int',
    ];

    protected $fillable = [
        'name',
        'short_description',
        'description',
        'created_by',
        'blog_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function tags()
    {
        return $this->hasMany(Tag::class);
    }

    public function users()
    {
        return $this->hasMany(User::class, 'active_blog');
    }
}
