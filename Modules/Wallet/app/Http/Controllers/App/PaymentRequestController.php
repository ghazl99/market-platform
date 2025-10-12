<?php

namespace Modules\Wallet\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Wallet\Services\App\PaymentRequestService;
use Modules\Wallet\Http\Requests\App\PaymentRequestStore;

class PaymentRequestController extends Controller
{

    public function __construct(
        protected PaymentRequestService $paymentRequestService
    ) {}
    public function store(PaymentRequestStore $request)
    {
        try {
            $this->paymentRequestService->storeDeposit($request->validated());

            return redirect()->back()->with('success',  __('Created successfully'));
        } catch (\Exception $e) {
            // Log the error or return JSON response for AJAX requests
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

}
