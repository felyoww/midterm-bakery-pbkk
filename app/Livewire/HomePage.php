<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Category;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Home Page - Aetherial Bakery')]
class HomePage extends Component
{
    public function render()
    {
        $categories = Category::where('is_active', 1)->get();
        return view('livewire.home-page', [
            'categories' => $categories,
        ]);
    }
}
