<?php

namespace Modules\Wallet\Repositories\App;

use Modules\Wallet\Models\Wallet;

class WalletModelRepository implements WalletRepository
{
    public function create(array $data): Wallet
    {
        return Wallet::create($data);
    }
}
