<?php

namespace Modules\Wallet\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Modules\Wallet\Models\PaymentRequest;

class PaymentRequestController extends Controller implements HasMiddleware
{
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
        $search = $request->get('search');

        $query = PaymentRequest::with(['wallet.user', 'wallet.store', 'approvedBy'])
            ->orderBy('created_at', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('original_amount', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhereHas('wallet.user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $paymentRequests = $query->paginate(10);

        if ($request->ajax()) {
            $html = view('wallet::dashboard.payment-requests.dataTables', compact('paymentRequests'))->render();
            $pagination = $paymentRequests->hasPages() ? $paymentRequests->links()->toHtml() : '';

            return response()->json([
                'html' => $html,
                'pagination' => $pagination,
                'hasPages' => $paymentRequests->hasPages(),
            ]);
        }

        return view('wallet::dashboard.payment-requests.index', compact('paymentRequests'));
    }

    /**
     * Update payment request status (approve/reject).
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'notes' => 'nullable|string|max:500'
        ]);

        $paymentRequest = PaymentRequest::findOrFail($id);

        $userId = Auth::check() ? Auth::user()->id : 1;

        $paymentRequest->update([
            'status' => $request->status,
            'approved_by' => $userId
        ]);

        $message = $request->status === 'approved'
            ? __('Payment request approved successfully')
            : __('Payment request rejected successfully');

        return redirect()->route('dashboard.dashboard.payment-requests.index')
            ->with('success', $message);
    }
}
