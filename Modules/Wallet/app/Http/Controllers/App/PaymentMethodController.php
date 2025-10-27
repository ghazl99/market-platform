<?php

namespace Modules\Wallet\Http\Controllers\App;

use Illuminate\Http\Request;
use Modules\Store\Models\Store;
use App\Http\Controllers\Controller;
use Mgcodeur\CurrencyConverter\Facades\CurrencyConverter;
use Modules\Wallet\Models\PaymentMethod;
use Modules\Wallet\Services\App\PaymentMethodService;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PaymentMethodController extends Controller
{

    public function __construct(
        protected PaymentMethodService $paymentMethodService
    ) {}

    public function showImage(Media $media)
    {
        $path = $media->getPath();

        if (! file_exists($path)) {
            abort(404);
        }

        return response()->file($path);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $store = Store::currentFromUrl()->firstOrFail();

        $paymentMethods = $this->paymentMethodService->getForCurrentStore($store);
        return view('themes.' . current_theme_name_en() . '.add-balance', compact('paymentMethods'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('wallet::app.paymentMethod.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show(PaymentMethod $paymentMethod)
    {

        return view('themes.' . current_theme_name_en() . '.payment-details', compact('paymentMethod'));
    }

    public function convertCurrency(Request $request)
    {
        $amount = $request->input('amount', 0);
        $from = $request->input('from', 'USD');
        $to = 'USD';

        $converted = CurrencyConverter::convert()->from($from)->to($to)->amount($amount)->get();

        return response()->json([
            'converted' => $converted,
        ]);
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
