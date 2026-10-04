<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'name', 'slug', 'description', 'price', 'stock'];

    protected static function booted(): void
    {
        static::saving(function (Product $product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name) . '-' . Str::random(5);
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // ---------- Relationships (REQUIREMENT 3) ----------

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    // ---------- Scopes (REQUIREMENT 3: minimal 1 scope) ----------

    /**
     * Scope #1: hanya produk yang stoknya masih ada.
     * Dipakai: Product::inStock()->get();
     */
    public function scopeInStock(Builder $query): Builder
    {
        return $query->where('stock', '>', 0);
    }

    /**
     * Scope #2 (tambahan): pencarian nama produk.
     * Dipakai: Product::search('mouse')->get();
     */
    public function scopeSearch(Builder $query, string $keyword): Builder
    {
        return $query->where('name', 'like', '%' . $keyword . '%');
    }

    /**
     * Rata-rata rating, dihitung dari relasi reviews (BONUS: eager loading
     * demo — lihat README bagian "Eager Loading").
     */
    public function averageRating(): float
    {
        return round($this->reviews->avg('rating') ?? 0, 1);
    }
}
