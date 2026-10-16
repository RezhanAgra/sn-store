<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class PublicLayout extends Component
{
    /**
     * @param  array<int, array{name: string, slug: string}>  $categories
     */
    public function __construct(public array $categories = [])
    {
        $this->categories = $categories ?: config('storefront.categories');
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.public');
    }
}
