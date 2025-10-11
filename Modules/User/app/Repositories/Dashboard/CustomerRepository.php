<?php

namespace Modules\User\Repositories\Dashboard;

interface CustomerRepository
{
    public function index(?string $search = null): mixed;
}
