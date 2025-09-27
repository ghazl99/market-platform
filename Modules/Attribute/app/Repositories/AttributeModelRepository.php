<?php

namespace Modules\Attribute\Repositories;

use Modules\Attribute\Models\Attribute;

class AttributeModelRepository implements AttributeRepository
{
    public function index()
    {
        return Attribute::all();
    }

    public function create(array $data): Attribute
    {
        return Attribute::firstOrCreate($data);
    }

    public function findByName(string $name, string $locale): ?Attribute
    {
        return Attribute::where("name->$locale", $name)->first();
    }
}
