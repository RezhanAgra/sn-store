<?php

namespace App\View\Components;

use App\Models\Category;
use App\Services\CartService;
use App\Services\CategoryService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;
use Illuminate\View\View;

class PublicLayout extends Component
{
    /**
     * @var Collection<int, Category>
     */
    public Collection $categories;

    public int $cartCount;

    public function __construct(CategoryService $categoryService, CartService $cartService)
    {
        $this->categories = $categoryService->all();
        $this->cartCount = $cartService->count(auth()->user());
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.public');
    }
}
