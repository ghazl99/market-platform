<?php

namespace Modules\Store\Repositories\Admin;

interface ThemeRepository
{
    public function all();
    public function create(array $data);
}
