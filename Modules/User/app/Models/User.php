<?php

namespace Modules\User\Models;

use Laravel\Scout\Searchable;
use Modules\Store\Models\Store;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Modules\User\Database\Factories\OwnerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia; // use Modules\User\Database\Factories\UserFactory;

class User extends Authenticatable implements HasMedia, MustVerifyEmail
{
    use HasFactory, HasRoles, HasTranslations, InteractsWithMedia, Notifiable, Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'birth_date',
        'address',
        'city',
        'postal_code',
        'country',
        'language',
        'timezone',
        'email_notifications',
        'sms_notifications',
        'last_login_at',
        'last_updated_at_password',
        'debt_limit',
        'group_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_updated_at_password' => 'datetime',
            'last_login_at' => 'datetime',
            'birth_date' => 'date',
            'password' => 'hashed',
            'debt_limit' => 'decimal:2',
            'name' => 'array',
            'address' => 'array',
            'city' => 'array',
        ];
    }

    /**
     * The attributes that should be translatable.
     *
     * @var array
     */
    public $translatable = ['name', 'address', 'city'];

    protected static function newFactory()
    {
        return OwnerFactory::new();
    }


    public function getLastUpdatedAtPasswordInStoreTimezoneAttribute()
    {
        if (!$this->last_updated_at_password) {
            return null;
        }

        // استرجاع المتجر الحالي من الدومين
        $store = Cache::remember('current_store', 60, function () {
            return Store::currentFromUrl()->first();
        });

        if ($store) {
            return $this->last_updated_at_password->timezone($store->timezone);
        }

        // إذا ما في متجر، رجّعها UTC
        return $this->last_updated_at_password->timezone('UTC');
    }

    public function getProfilePhotoUrlAttribute()
    {
        $media = $this->getFirstMedia('profile_photo_images');
        if ($media) {
            return route('profile-photo.image', $media);
        }

        $firstLetter = strtoupper(mb_substr($this->name, 0, 1));
        $name = urlencode($firstLetter);

        return "https://ui-avatars.com/api/?name={$name}&background=0D8ABC&color=fff&size=256";
    }

    /**
     * Get all stores where the user has the "owner" role.
     */
    public function ownedStores()
    {
        return $this->belongsToMany(Store::class, 'store_users')
            ->withPivot('is_active')
            ->withTimestamps()
            ->whereHas('users.roles', fn($q) => $q->where('name', 'owner'));
    }

    /**
     * علاقة مع المتاجر التي ينتمي إليها المستخدم
     */
    public function memberStores(): BelongsToMany
    {
        return $this->belongsToMany(Store::class, 'store_users')
            ->withPivot('is_active')
            ->withTimestamps()
            ->whereHas('users.roles', fn($q) => $q->where('name', 'staff'));
    }

    /**
     * الحصول على جميع المتاجر (المملوكة والعضو فيها)
     */
    public function stores(): BelongsToMany
    {
        return $this->belongsToMany(Store::class, 'store_users')
            ->withPivot('is_active')
            ->withTimestamps();
    }
    public function walletForStore($storeId = null)
    {
        $storeId ??= \Modules\Store\Models\Store::currentFromUrl()->first()?->id;

        return $this->hasOne(\Modules\Wallet\Models\Wallet::class)
            ->where('store_id', $storeId);
    }

    public function wallets()
    {
        return $this->hasMany(\Modules\Wallet\Models\Wallet::class);
    }

    public function toSearchableArray()
    {
        $array = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
        ];

        return $array;
    }

    /**
     * Get the group that the user belongs to.
     */
    public function group()
    {
        return $this->belongsTo(\Modules\User\Models\Group::class);
    }
}
