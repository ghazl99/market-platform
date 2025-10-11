<?php

namespace Modules\Wallet\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('wallet::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('wallet::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('wallet::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
