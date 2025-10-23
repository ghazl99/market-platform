<?php

namespace Modules\Store\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Store\Database\Factories\StoreSettingFactory;

class StoreSetting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'store_id',
        'key',
        'value',
    ];

    /**
     * Get the store that owns the setting.
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    
    /**
     * Get the setting value.
     * If the value is a JSON array (translated), it returns the value for the specified locale.
     * Otherwise, it returns the raw value.
     *
     * @param string $locale
     * @return mixed
     */
    public function getValue(string $locale = 'en')
    {
        if (is_array($this->value)) {
            return $this->value[$locale] ?? null;
        }
        return $this->value; // plain value
    }

    /**
     * Set the setting value.
     * If the value is translatable (JSON array), it sets the value for the specified locale.
     * Otherwise, it sets the plain value.
     *
     * @param mixed $value
     * @param string $locale
     * @return void
     */
    public function setValue($value, string $locale = 'en'): void
    {
        if (is_array($this->value) || is_array($value)) {
            $current = $this->value ?? [];
            // If $value is an array with locales, take the value for the given locale; otherwise take the raw value
            $current[$locale] = is_array($value) ? $value[$locale] ?? null : $value;
            $this->value = $current;
        } else {
            $this->value = $value; // plain value
        }
    }
}
