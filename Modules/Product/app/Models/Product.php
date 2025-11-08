<?php

namespace Modules\Product\Models;

use App\Group;
use Laravel\Scout\Searchable;
use Modules\Store\Models\Store;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Facades\Auth;
use Modules\Category\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Modules\Attribute\Models\Attribute;
use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Product\Database\Factories\ProductFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

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
        'capital',
        'status',
        'product_type',
        'notes',
        'is_active',
        'is_featured',
        'sku',
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
        'price' => 'decimal:20',
        'original_price' => 'decimal:20',
        'capital' => 'decimal:20',
        'views_count' => 'integer',
        'orders_count' => 'integer',
        'min_quantity' => 'integer',
        'max_quantity' => 'integer',
    ];

    public $translatable = ['name', 'description'];

    /**
     * Get price attribute without trailing zeros
     */
    public function getPriceAttribute($value)
    {
        // Get raw value from database to avoid infinite loop
        $rawValue = $this->getRawOriginal('price') ?? $value;

        if ($rawValue === null || $rawValue === '') {
            return null;
        }

        // Convert to string and remove trailing zeros
        $formatted = (string)(float)$rawValue;
        return rtrim(rtrim($formatted, '0'), '.');
    }

    /**
     * Get original_price attribute without trailing zeros
     */
    public function getOriginalPriceAttribute($value)
    {
        // Get raw value from database to avoid infinite loop
        $rawValue = $this->getRawOriginal('original_price') ?? $value;

        if ($rawValue === null || $rawValue === '') {
            return null;
        }

        // Convert to string and remove trailing zeros
        $formatted = (string)(float)$rawValue;
        return rtrim(rtrim($formatted, '0'), '.');
    }

    public function getPriceWithGroupProfitAttribute()
    {
        $basePrice = $this->price;

        $user = Auth::user();

        $store = function_exists('current_store') ? current_store() : null;
        $profitPercentage = 0;

        // إذا المستخدم عنده مجموعة → خذ نسبتها
        if ($user && $user->group) {
            $profitPercentage = $user->group->profit_percentage;
        } else {
            $profitPercentage = \App\Group::getDefaultGroup()?->profit_percentage ?? 0;
        }
        // احسب السعر النهائي
        return $basePrice + ($basePrice * $profitPercentage / 100);
    }


    protected static function newFactory(): ProductFactory
    {
        return ProductFactory::new();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('product_images')
            ->singleFile();
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
                $url = $media->getUrl();
                // Ensure the URL is accessible via public storage
                if (str_starts_with($url, '/storage/')) {
                    return $url;
                }
                // If URL is relative, prepend storage path
                if (!str_starts_with($url, 'http')) {
                    return '/storage/' . ltrim($url, '/');
                }
                return $url;
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Error getting media URL: ' . $e->getMessage());
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
