<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Modules\Attribute\Models\Attribute;
use Modules\Category\Models\Category;
use Modules\Store\Models\Store;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Product extends Model implements HasMedia
{
    use HasFactory, HasTranslations, InteractsWithMedia, Searchable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'store_id',
        'name',
        'description',
        'original_price',
        'price',
        'status',
        'views_count',
        'orders_count',
        'min_quantity',
        'max_quantity',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public $translatable = ['name', 'description'];

    public function getCreatedAtInStoreTimezoneAttribute()
    {
        if ($this->store) {
            // هنا نستخدم الـ accessor $store->timezone
            return $this->created_at->timezone($this->store->timezone);
        }

        return $this->created_at->timezone('UTC');
    }

    public function getUpdatedAtInStoreTimezoneAttribute()
    {
        if ($this->store) {
            return $this->updated_at->timezone($this->store->timezone);
        }

        return $this->updated_at->timezone('UTC');
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_products');
    }

    public function attributes()
    {
        return $this->belongsToMany(
            Attribute::class,
            'attribute_products'
        )->withPivot('value')
            ->withTimestamps();
    }

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'status' => $this->status,
        ];
    }
}
