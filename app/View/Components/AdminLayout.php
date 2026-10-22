<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AdminLayout extends Component
{
    /**
     * @param  array<int, array{label: string, route: string|null, active: string, icon: string}>  $menu
     */
    public function __construct(public array $menu = [])
    {
        $this->menu = $menu ?: config('admin.menu');
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.admin');
    }
}
