<?php

namespace Modules\User\Repositories\Dashboard;

use Modules\User\Models\User;

class CustomerModelRepository implements CustomerRepository
{
    public function index(?string $search = null): mixed
    {
        $store = \Modules\Store\Models\Store::currentFromUrl()->first();

        if (! $store) {
            abort(404, 'Store not found');
        }

        // البحث عن جميع المستخدمين المرتبطين بهذا المتجر
        $query = User::query()
            ->whereHas('stores', fn ($q) => $q->where('stores.id', $store->id))
            ->with(['roles', 'stores']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }
}
