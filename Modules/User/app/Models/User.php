<?php

namespace Modules\User\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Scout\Searchable;
use Modules\Store\Models\Store;
use Spatie\MediaLibrary\HasMedia; // use Modules\User\Database\Factories\UserFactory;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Translatable\HasTranslations;

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
        'last_login_at',
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
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
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
            ->whereHas('users.roles', fn ($q) => $q->where('name', 'owner'));
    }

    /**
     * علاقة مع المتاجر التي ينتمي إليها المستخدم
     */
    public function memberStores(): BelongsToMany
    {
        return $this->belongsToMany(Store::class, 'store_users')
            ->withPivot('is_active')
            ->withTimestamps()
            ->whereHas('users.roles', fn ($q) => $q->where('name', 'staff'));
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

    public function toSearchableArray()
    {
        $array = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
        ];

        return $array;
    }
}
