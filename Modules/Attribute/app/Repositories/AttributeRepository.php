<?php

namespace Modules\Attribute\Repositories;

use Modules\Attribute\Models\Attribute;

interface AttributeRepository
{
    public function index();

    public function create(array $data): Attribute;

    public function findByName(string $name, string $locale): ?Attribute;

    // public function update(array $data, Attribute $store): bool;
}
