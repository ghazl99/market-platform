<?php

namespace Modules\Store\Models;

use Modules\Store\Models\Store;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class Theme extends Model
{
    use HasFactory,HasTranslations;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name'];
    public $translatable = ['name'];

    protected $casts = [
        'name' => 'array',    // لتحويل JSON إلى مصفوفة تلقائياً
    ];
    public function stores()
    {
        return $this->hasMany(Store::class);
    }
}
