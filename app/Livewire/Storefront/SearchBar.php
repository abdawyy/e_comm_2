<?php

namespace App\Livewire\Storefront;

use App\Models\Product;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;
use Throwable;

class SearchBar extends Component
{
    public string $q = '';

    public function render()
    {
        $results = collect();

        try {
            if (Schema::hasTable('products') && mb_strlen(trim($this->q)) >= 2) {
                $term = '%'.trim($this->q).'%';
                $results = Product::query()
                    ->where('is_active', true)
                    ->where(function ($query) use ($term) {
                        $query->where('name->en', 'like', $term)
                            ->orWhere('name->ar', 'like', $term)
                            ->orWhere('sku', 'like', $term);
                    })
                    ->limit(6)
                    ->get();
            }
        } catch (Throwable) {
            $results = collect();
        }

        return view('livewire.storefront.search-bar', [
            'results' => $results,
        ]);
    }
}
