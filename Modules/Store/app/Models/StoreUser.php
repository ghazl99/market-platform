<?php

namespace Modules\Store\Models;

use Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Store\Database\Factories\StoreUserFactory;

// use Modules\Store\Database\Factories\StoreUserFactory;

class StoreUser extends Model
{
        use HasFactory;

    protected $fillable = [
        'store_id',
        'user_id',
        'is_active',
    ];

    protected $casts = [
        'permissions' => 'array',
        'is_active' => 'boolean',
    ];
    protected static function newFactory()
    {
        return StoreUserFactory::new();
    }
    /**
     * علاقة مع المتجر
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * علاقة مع المستخدم
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * التحقق من أن المستخدم نشط
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }
}
