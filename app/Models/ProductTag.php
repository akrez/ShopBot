<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductTag
 *
 * @property int $product_id
 * @property string $tag_name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Product $product
 * @property Tag $tag
 */
class ProductTag extends Model
{
    protected $table = 'product_tag';

    public $incrementing = false;

    protected $casts = [
        'product_id' => 'int',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class, 'tag_name');
    }
}
