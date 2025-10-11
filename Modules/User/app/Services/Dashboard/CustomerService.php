<?php

namespace Modules\User\Services\Dashboard;

use Modules\User\Repositories\Dashboard\CustomerRepository;

class CustomerService
{
    public function __construct(
        protected CustomerRepository $customerRepository
    ) {}

    public function getCustomers(?string $search = null)
    {
        return $this->customerRepository->index($search);
    }
}
