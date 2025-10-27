<?php

namespace Modules\Store\Repositories\Admin;

use Modules\Store\Models\Theme;

class ThemeModelRepository implements ThemeRepository
{
    public function all()
    {
        return Theme::all();
    }

    public function create(array $data)
    {
        return Theme::create($data);
    }
}
