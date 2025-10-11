<?php

namespace Modules\Category\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;
use Modules\Category\Database\Factories\CategoryFactory;
use Modules\Product\Models\Product;
use Modules\Store\Models\Store;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Category extends Model implements HasMedia
{
    use HasFactory, HasTranslations, InteractsWithMedia, Searchable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'description',
        'parent_id',
        'store_id',
        'icon',
        'is_active',
        'sort_order',
        'seo_title',
        'keywords',
        'seo_description'
    ];

    public $translatable = ['name', 'description'];

    protected static function newFactory(): CategoryFactory
    {
        return CategoryFactory::new();
    }
    
    public function products()
    {
        return $this->belongsToMany(Product::class, 'category_products');
    }

    public function store()
    {
        return $this->belongsTo(Store::class); // Assuming you have a Store model
    }

    /**
     * Get the parent category.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the child categories.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
}
