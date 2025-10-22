<?php

namespace Modules\Store\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;
use Modules\Product\Models\Product;
use Modules\User\Models\User;
use Spatie\MediaLibrary\HasMedia;
use Modules\Wallet\Models\PaymentMethod;
use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\InteractsWithMedia;
use Modules\Store\Database\Factories\StoreFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Store extends Model implements HasMedia
{
    use HasFactory, HasTranslations, InteractsWithMedia;

    protected $fillable = [
        'name',
        'domain',
        'type',
        'description',
        'status',
        'theme',
        'settings',
    ];

    public $translatable = ['name', 'description'];

    protected $casts = [
        'settings' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    protected static function newFactory()
    {
        return StoreFactory::new();
    }
    /**
     * Scope to get store from current request URL (subdomain)
     */
    public function scopeCurrentFromUrl($query)
    {
        $host = request()->getHost(); // e.g., my-pharma.market-platform.localhost
        $mainDomain = app()->environment('production')
            ? config('app.main_domain', 'soqsyria.com')
            : 'market-platform.localhost';
        // إذا الدومين هو الرئيسي، رجع null
        if ($host === $mainDomain) {
            return $query->whereNull('id');
        }

        $subdomain = explode('.', $host)[0]; // أخذ أول جزء من الدومين

        return $query->where('domain', $subdomain);
    }

    /**
     * Get timezone from settings, default to UTC
     */
    public function getTimezoneAttribute(): string
    {
        return $this->settings['timezone'] ?? 'UTC';
    }

    /**
     * Set timezone in settings
     */
    public function setTimezoneAttribute(string $timezone): void
    {
        $settings = $this->settings ?? [];
        $settings['timezone'] = $timezone;
        $this->settings = $settings;
    }

    public function getCreatedAtInStoreTimezoneAttribute()
    {
        return $this->created_at->timezone($this->timezone);
    }

    public function getUpdatedAtInStoreTimezoneAttribute()
    {
        return $this->updated_at->timezone($this->timezone);
    }

    public function getStoreUrlAttribute(): string
    {
        if (app()->environment('production')) {
            return 'https://' . $this->domain; // custom domain
        }

        // Local domain-based
        return "http://{$this->domain}.market-platform.localhost";
    }

    // رابط لوحة التحكم
    public function getDashboardUrlAttribute(): string
    {
        if (app()->environment('production')) {
            return 'https://' . $this->domain . '/dashboard';
        }

        return "http://{$this->domain}.market-platform.localhost/dashboard";
    }

    /**
     * علاقة مع مستخدمي المتجر
     */
    public function storeUsers(): HasMany
    {
        return $this->hasMany(StoreUser::class);
    }

    /**
     * علاقة مع المستخدمين عبر جدول pivot
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'store_users')
            ->withPivot('is_active')
            ->withTimestamps();
    }

    /**
     * Get the main owner of the store.
     */
    public function owners(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'store_users')
            ->withPivot('is_active')
            ->withTimestamps()
            ->whereHas('roles', fn($q) => $q->where('name', 'owner'));
    }

    /** * Get all staff members of the store. */
    public function staff(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'store_users')
            ->withPivot('is_active')
            ->withTimestamps()->whereHas('roles', fn($q) => $q->where('name', 'staff'));
    }

    /**
     * التحقق من حالة المتجر
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * الحصول على رابط المتجر بالـ ID
     */
    public function getAlternativeStoreUrlAttribute(): string
    {
        return "http://{$this->domain}.market-platform.localhost";
    }

    public function getAlternativeDashboardUrlAttribute(): string
    {
        return "http://{$this->domain}.market-platform.localhost/dashboard";
    }

    public function getShortStoreUrlAttribute(): string
    {
        return "http://{$this->domain}.market-platform.localhost";
    }

    public function getStoreByIdUrlAttribute(): string
    {
        $appUrl = config('app.url', 'http://127.0.0.1:8000');
        return $appUrl . '/view/' . $this->id;
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * الحصول على جميع روابط المتجر
     */
    public function getAllUrlsAttribute(): array
    {
        return [
            'production' => [
                'store' => $this->store_url,
                'dashboard' => $this->dashboard_url,
            ],
            'local' => [
                'store' => $this->alternative_store_url,
                'dashboard' => $this->alternative_dashboard_url,
                'short' => $this->short_store_url,
                'by_id' => $this->store_by_id_url,
            ],
        ];
    }

    /**
     * Get store from current URL
     */
    public static function currentFromUrl()
    {
        $host = request()->getHost();

        // Extract subdomain from host
        if (strpos($host, '.market-platform.localhost') !== false) {
            $subdomain = str_replace('.market-platform.localhost', '', $host);
            return static::where('domain', $subdomain);
        }

        // If no subdomain found, return empty query
        return static::where('id', 0);
    }
}
