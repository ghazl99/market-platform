<?php

namespace Modules\Core\Services\Admin;

use Modules\Store\Models\Store;
use Modules\Store\Repositories\Admin\StoreRepository;

class HomeService
{
    public function __construct(
        protected StoreRepository $storeRepository
    ) {}

    /**
     * تحديد إذا كان الدومين الرئيسي
     */
    public function isMainDomain(string $host, string $mainDomain): bool
    {
        return strtolower($host) === strtolower($mainDomain);
    }

    /**
     * استخراج الدومين الفرعي
     */
    public function extractSubdomain(string $host): string
    {
        $parts = explode('.', $host);

        return $parts[0] ?? '';
    }

    /**
     * الحصول على المتجر حسب الدومين
     */
    public function getStoreByHost(string $host): ?Store
    {
        $storeDomain = $this->extractSubdomain($host);

        return $this->storeRepository->findStoreByDomain($storeDomain);
    }
}
