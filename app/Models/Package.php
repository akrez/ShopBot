<?php

namespace App\Models;

use App\Enums\Package\PackageStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Package extends Model
{
    use HasFactory;

    protected $table = 'packages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'package_status',
        'price',
        'color_id',
        'product_id',
        'guaranty',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'package_status' => PackageStatus::class,
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
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

    public function scopeFilterNotDeactive(Builder $query)
    {
        $query = $query->where('package_status', '<>', PackageStatus::DEACTIVE->value);
    }
}
