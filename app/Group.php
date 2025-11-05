<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Group extends Model
{
    use HasTranslations;

    protected $fillable = [
        'name',
        'profit_percentage',
        'is_default',
        'store_id',
    ];

    /**
     * The attributes that should be translatable.
     *
     * @var array
     */
    public $translatable = ['name'];

    protected $casts = [
        'profit_percentage' => 'decimal:2',
        'is_default' => 'boolean',
        'name' => 'array',
    ];

    /**
     * Get the store that owns the group.
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(\Modules\Store\Models\Store::class);
    }

    /**
     * Get the users for the group.
     */
    public function users(): HasMany
    {
        return $this->hasMany(\Modules\User\Models\User::class);
    }

    /**
     * Get the default group.
     */
    public static function getDefaultGroup()
    {
        $store = function_exists('current_store') ? current_store() : null;

        return self::where('is_default', true)->where('store_id', $store->id)->first();
    }

    /**
     * Scope to get non-default groups.
     */
    public function scopeNonDefault($query)
    {
        return $query->where('is_default', false);
    }
}
