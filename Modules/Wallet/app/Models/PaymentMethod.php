<?php

namespace Modules\Wallet\Models;

use Modules\Store\Models\Store;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Wallet\Database\Factories\PaymentMethodFactory;
use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;

class PaymentMethod extends Model implements HasMedia

{
    use HasFactory, HasTranslations, InteractsWithMedia;

    protected $table = 'payment_methods';
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'store_id',
        'gateway',
        'image',
        'currencies',
        'recipient_name',
        'account_number',
        'bank_name',
        'transfer_number',
        'instructions',
        'is_active',
    ];
    public $translatable = ['name', 'recipient_name', 'bank_name', 'instructions'];

    protected $casts = [
        'name' => 'array',
        'currencies' => 'array',
        'recipient_name' => 'array',
        'bank_name' => 'array',
        'instructions' => 'array',
        'is_active' => 'boolean',
    ];

    protected static function newFactory()
    {
        return PaymentMethodFactory::new();
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
