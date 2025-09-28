<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Modules\Order\Models\Order;

class OrderExport implements FromCollection, WithHeadings
{
    protected $orders;

    protected $statusLabels = [
        'pending' => 'Pending',
        'confirmed' => 'Confirmed',
        'completed' => 'Completed',
        'canceled' => 'Canceled',
    ];

    protected $paymentLabels = [
        'paid' => 'Paid',
        'unpaid' => 'Unpaid',
        'partially_paid' => 'Partially Paid',
    ];

    public function __construct()
    {
        $this->orders = Order::with('items.product', 'user')->get();
    }

    public function collection()
    {
        $collection = collect();

        foreach ($this->orders as $order) {
            foreach ($order->items as $item) {
                $collection->push([
                    __('Order ID')       => $order->id,
                    __('User')           => $order->user?->name ?? '-',
                    __('Player ID')      => $order->user?->player_id ?? '-',
                    __('Status')         => __($this->statusLabels[$order->status] ?? $order->status),
                    __('Payment Status') => __($this->paymentLabels[$order->payment_status] ?? $order->payment_status),
                    __('Product Name')   => $item->product?->name ?? '-',
                    __('Price')          => $item->product?->price ?? 0,
                    __('Quantity')       => $item->quantity,
                    __('Total')          => ($item->product?->price ?? 0) * $item->quantity,
                    __('Created At')     => $order->created_at->format('Y-m-d H:i:s'),
                ]);
            }
        }

        return $collection;
    }

    public function headings(): array
    {
        return [
            __('Order ID'),
            __('User'),
            __('Player ID'),
            __('Status'),
            __('Payment Status'),
            __('Product Name'),
            __('Price'),
            __('Quantity'),
            __('Total'),
            __('Created At'),
        ];
    }
}
