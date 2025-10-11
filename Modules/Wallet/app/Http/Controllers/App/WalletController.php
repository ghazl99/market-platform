<?php

namespace Modules\Wallet\Http\Controllers\App;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Modules\Wallet\Pipelines\AddBalance;
use Modules\Wallet\Pipelines\LogDepositTransaction;
use Modules\Wallet\Services\App\WalletService;

class WalletController extends Controller
{

    public function __construct(
        protected WalletService $walletService
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $filters = [
            'date_from' => $request->input('date_from') ?: null,
            'date_to'   => $request->input('date_to') ?: null,
            'quick'     => $request->input('quick') ?: null,
            'type'      => $request->input('type') ?: null,
        ];

        $transactions = $this->walletService->getTransactions($filters, 10);
        return view('wallet::app.wallet.index', compact('transactions'));
    }

    public function deposit(array $data)
    {
        return DB::transaction(function () use ($data) {
            return app(\Illuminate\Pipeline\Pipeline::class)
                ->send($data)
                ->through([
                    AddBalance::class,
                    LogDepositTransaction::class,
                ])
                ->thenReturn();

        });
    }
}
