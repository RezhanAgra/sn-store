<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = ['category_id', 'name', 'description', 'price', 'stock', 'image'];

    /**
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * URL gambar produk, atau gambar placeholder jika belum diunggah.
     */
    public function getImageUrlAttribute(): string
    {
        return $this->image
            ? Storage::disk('public')->url($this->image)
            : 'https://placehold.co/120x120?text=No+Image';
    }
}
