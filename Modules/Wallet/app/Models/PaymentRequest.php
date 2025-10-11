<?php

namespace Modules\Wallet\Models;

use Modules\User\Models\User;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentRequest extends Model implements HasMedia
{
    use HasFactory, HasTranslations, InteractsWithMedia;
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'wallet_id',
        'approved_by',
        'original_amount',
        'original_currency',
        'amount_usd',
        'exchange_rate',
        'status',
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(\Modules\User\Models\User::class, 'approved_by');
    }
}
