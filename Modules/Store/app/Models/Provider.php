<?php

namespace Modules\Store\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Provider extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'api_url',
        'api_token',
        'description',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the stores that are linked to this provider.
     */
    public function stores(): BelongsToMany
    {
        return $this->belongsToMany(Store::class, 'store_providers')
            ->withPivot('is_active')
            ->withTimestamps();
    }

    /**
     * Get only active stores linked to this provider.
     */
    public function activeStores(): BelongsToMany
    {
        return $this->stores()->wherePivot('is_active', true);
    }
}

