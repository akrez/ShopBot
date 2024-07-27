<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Enums\Blog\BlogStatus;
use App\Enums\Gallery\GalleryCategory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

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
 * @property Collection|ProductTag[] $productTags
 * @property Collection|ProductProperty[] $productProperties
 * @property Collection|Gallery[] $galleries
 * @property Collection|User[] $users
 */
class Blog extends Model
{
    use HasFactory;

    protected $table = 'blogs';

    protected $casts = [
        'blog_status' => BlogStatus::class,
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

    public function productProperties()
    {
        return $this->hasMany(ProductProperty::class);
    }

    public function productTags()
    {
        return $this->hasMany(ProductTag::class);
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }

    public function users()
    {
        return $this->hasMany(User::class, 'active_blog');
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function logo(): MorphOne
    {
        return $this->morphOne(Gallery::class, 'gallery')->where('gallery_category', GalleryCategory::BLOG_LOGO->value);
    }

    public function logoUrl(): ?string
    {
        return $this->logo()?->filterIsSelected()->first()?->getUrl();
    }
}
