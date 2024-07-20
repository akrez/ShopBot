<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Enums\Product\ProductStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Product
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $code
 * @property int $blog_id
 * @property string|null $product_status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'product_status',
    ];

    protected function casts(): array
    {
        return [
            'product_status' => ProductStatus::class,
        ];
    }

    public function productTags()
    {
        return $this->hasMany(ProductTag::class, 'product_id', 'id');
    }

    public function productProperties()
    {
        return $this->hasMany(ProductProperty::class, 'product_id', 'id');
    }

    public function blog(): BelongsTo
    {
        return $this->belongsTo(Blog::class);
    }

    public function images()
    {
        return $this->morphMany(Gallery::class, 'gallery');
    }
}
