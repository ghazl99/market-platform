<?php

namespace Modules\Wallet\Models;

use Modules\Store\Models\Store;
use Illuminate\Database\Eloquent\Model;
use Modules\Wallet\Models\PaymentRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// use Modules\Wallet\Database\Factories\WalletFactory;

class Wallet extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['user_id','store_id', 'balance'];

    public function user()
    {
        return $this->belongsTo(\Modules\User\Models\User::class);
    }
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function paymentRequests()
    {
        return $this->hasMany(PaymentRequest::class);
    }
    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }
}
