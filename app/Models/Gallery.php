<?php

namespace App\Models;

use App\Enums\Gallery\GalleryCategory;
use App\Services\GalleryService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gallery extends Model
{
    use HasFactory;

    protected $table = 'galleries';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $primaryKey = 'name';

    protected $casts = [
        'gallery_category' => GalleryCategory::class,
        'selected_at' => 'datetime',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [];

    public function getUrl($whmq = null): string
    {
        return resolve(GalleryService::class)->getUrlByModel($this, $whmq);
    }

    public function getBaseUrl(): string
    {
        return resolve(GalleryService::class)->getBaseUrl($this->gallery_category->value);
    }

    public function blog(): BelongsTo
    {
        return $this->belongsTo(Blog::class);
    }

    public function scopeFilterIsSelected(Builder $query)
    {
        $query = $query->whereNotNull('selected_at');
    }

    public function scopeOrderDefault(Builder $query)
    {
        $query = $query
            ->orderBy('selected_at', 'DESC')
            ->orderBy('gallery_order', 'DESC')
            ->orderBy('created_at', 'ASC');
    }
}
