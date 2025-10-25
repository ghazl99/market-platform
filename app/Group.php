<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    protected $fillable = [
        'name',
        'profit_percentage',
        'is_default',
    ];

    protected $casts = [
        'profit_percentage' => 'decimal:2',
        'is_default' => 'boolean',
    ];

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
        return self::where('is_default', true)->first();
    }

    /**
     * Scope to get non-default groups.
     */
    public function scopeNonDefault($query)
    {
        return $query->where('is_default', false);
    }
}
