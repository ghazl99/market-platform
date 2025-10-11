<?php

namespace Modules\Wallet\Repositories\App;

use Modules\Wallet\Models\Wallet;

interface WalletRepository
{
    public function create(array $data): Wallet;
    public function index(array $filters = []);
}
