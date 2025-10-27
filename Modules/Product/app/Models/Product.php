<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Modules\Attribute\Models\Attribute;
use Modules\Category\Models\Category;
use Modules\Product\Database\Factories\ProductFactory;
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
        'parent_id',
        'name',
        'description',
        'original_price',
        'price',
        'status',
        'product_type',
        'notes',
        'is_active',
        'is_featured',
        'sku',
        'stock_quantity',
        'weight',
        'dimensions',
        'seo_title',
        'seo_description',
        'views_count',
        'orders_count',
        'min_quantity',
        'max_quantity',
    ];

    protected $casts = [
        'status' => 'string',
        'product_type' => 'string',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'weight' => 'decimal:2',
        'views_count' => 'integer',
        'orders_count' => 'integer',
        'min_quantity' => 'integer',
        'max_quantity' => 'integer',
    ];

    public $translatable = ['name', 'description'];

    protected static function newFactory(): ProductFactory
    {
        return ProductFactory::new();
    }

    public function getImagePathAttribute()
    {
        $media = $this->getFirstMedia('product_images');
        return $media ? $media->getPath() : null;
    }

    public function getImageUrlAttribute()
    {
        $media = $this->getFirstMedia('product_images');
        if ($media) {
            try {
                return $media->getUrl();
            } catch (\Exception $e) {
                // \Log::error('Error getting media URL: ' . $e->getMessage());
                return null;
            }
        }
        return null;
    }

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

    public function parent()
    {
        return $this->belongsTo(Product::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Product::class, 'parent_id');
    }

    public function isSubProduct()
    {
        return !is_null($this->parent_id);
    }

    public function isParentProduct()
    {
        return $this->children()->exists();
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

    /**
     * Get product type options
     */
    public static function getProductTypeOptions()
    {
        return [
            'transfer' => __('Transfer'),
            'code' => __('Code'),
        ];
    }

    /**
     * Get linking type options
     */
    public static function getLinkingTypeOptions()
    {
        return [
            'automatic' => __('Automatic Linking'),
            'manual' => __('Manual Linking'),
        ];
    }

    /**
     * Get product type label
     */
    public function getProductTypeLabelAttribute()
    {
        return self::getProductTypeOptions()[$this->product_type] ?? $this->product_type;
    }

    /**
     * Get linking type label
     */
    public function getLinkingTypeLabelAttribute()
    {
        return self::getLinkingTypeOptions()[$this->linking_type] ?? $this->linking_type;
    }
}
