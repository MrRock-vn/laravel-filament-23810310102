<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    /**
     * Tên bảng bắt đầu bằng MSSV
     */
    protected $table = '23810310102_products';

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'stock_quantity',
        'image_path',
        'status',
        'discount_percent',  // Trường sáng tạo
    ];

    protected $casts = [
        'price'            => 'integer',
        'stock_quantity'   => 'integer',
        'discount_percent' => 'integer',
    ];

    /**
     * Tự động tạo slug từ name
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });

        static::updating(function (self $product) {
            if ($product->isDirty('name')) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    /**
     * Tính giá sau khi giảm giá (VNĐ)
     */
    public function getDiscountedPriceAttribute(): int
    {
        if ($this->discount_percent > 0) {
            return (int) ($this->price * (1 - $this->discount_percent / 100));
        }
        return $this->price;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
