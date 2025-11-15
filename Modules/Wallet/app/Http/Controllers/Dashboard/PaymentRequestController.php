<?php

namespace Modules\Wallet\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Store\Models\Store;
use Modules\Wallet\Models\PaymentRequest;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Modules\Wallet\Services\Dashboard\PaymentRequestService;

class PaymentRequestController extends Controller implements HasMiddleware
{
    public function __construct(
        protected PaymentRequestService $paymentRequestService
    ) {}
    public static function middleware(): array
    {
        return [
            new Middleware('role:admin|owner', only: ['index', 'update']),
        ];
    }

    /**
     * Display a listing of payment requests.
     */
    public function index(Request $request)
    {
        // الحصول على المتجر من middleware أو من URL
        $store = $request->attributes->get('store') ?? Store::currentFromUrl()->firstOrFail();
        $search = $request->get('search');

        $query = PaymentRequest::with(['wallet.user', 'wallet.store', 'approvedBy'])
            ->whereHas('wallet', function ($q) use ($store) {
                $q->where('store_id', $store->id);
            })
            ->orderBy('created_at', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('original_amount', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%")
                    ->orWhereHas('wallet.user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $paymentRequests = $query->paginate(10);

        if ($request->ajax()) {
            $html = view('wallet::dashboard.'. current_store()->type .'.payment-requests.dataTables', compact('paymentRequests'))->render();
            $pagination = $paymentRequests->hasPages() ? $paymentRequests->links()->toHtml() : '';

            return response()->json([
                'html' => $html,
                'pagination' => $pagination,
                'hasPages' => $paymentRequests->hasPages(),
            ]);
        }

        return view('wallet::dashboard.'. current_store()->type .'.payment-requests.index', compact('paymentRequests'));
    }

    /**
     * Update payment request status (approve/reject).
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'notes'  => 'nullable|string|max:500'
        ]);

        try {

            $this->paymentRequestService->processPaymentRequest($id, Auth::id(), $validated);

            return redirect()->back()->with('success', __('Updated successfully'));
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
