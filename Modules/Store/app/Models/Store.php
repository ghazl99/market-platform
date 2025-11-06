<?php

namespace Modules\Store\Models;

use Modules\User\Models\User;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Facades\Log;
use Modules\Product\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Modules\Wallet\Models\PaymentMethod;
use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\InteractsWithMedia;
use Modules\Store\Database\Factories\StoreFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Store extends Model implements HasMedia
{
    use HasFactory, HasTranslations, InteractsWithMedia;

    protected $fillable = [
        'name',
        'domain',
        'description',
        'type',
        'status',
        'theme_id',
        'settings',
    ];

    public $translatable = ['name', 'description'];

    protected $casts = [
        'settings' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function theme()
{
    return $this->belongsTo(Theme::class);
}
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
        $request = request();

        // إزالة www إذا موجود
        $host = preg_replace('/^www\./', '', $host);

        // Localhost subdomain support (market-platform.localhost)
        if (str_contains($host, 'market-platform.localhost')) {
            $subdomain = str_replace('.market-platform.localhost', '', $host);
            if (!empty($subdomain)) {
                return static::where('domain', $subdomain);
            }
        }

        // Support for 127.0.0.1 and localhost with subdomain in path or query
        if (in_array($host, ['127.0.0.1', 'localhost', '::1']) || str_starts_with($host, '127.0.0.1:') || str_starts_with($host, 'localhost:')) {
            // Try to get store from query parameter
            $storeId = $request->query('store_id');
            if ($storeId) {
                return static::where('id', $storeId);
            }

            // Try to get store from session
            $storeId = session('current_store_id');
            if ($storeId) {
                return static::where('id', $storeId);
            }

            // Try to get from URL path (e.g., /ar/dashboard -> get from auth user's store)
            $user =Auth::user();
            if ($user) {
                // Try to get store from user's stores relationship
                $userStore = $user->stores()->where('is_active', true)->first();
                if ($userStore) {
                    return static::where('id', $userStore->id);
                }
            }

            // Try to get first active store as fallback (for development)
            if (app()->environment('local')) {
                return static::where('is_active', true);
            }
        }

        // Production main domain or subdomain
        return static::where('domain', $host);
    }

    public function settingsStore()
    {
        return $this->hasMany(\Modules\Store\Models\StoreSetting::class);
    }

    /**
     * Get the groups for the store.
     */
    public function groups(): HasMany
    {
        return $this->hasMany(\App\Group::class);
    }
}
