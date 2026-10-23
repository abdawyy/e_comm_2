<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Product::query()->get()->map(fn (Product $product) => [
            'sku' => $product->sku,
            'name_en' => $product->getTranslation('name', 'en', false),
            'name_ar' => $product->getTranslation('name', 'ar', false),
            'price_per_unit' => (string) $product->price_per_unit,
            'unit' => $product->unit,
            'stock_quantity' => (string) $product->stock_quantity,
            'is_active' => $product->is_active ? '1' : '0',
        ]);
    }

    public function headings(): array
    {
        return ['sku', 'name_en', 'name_ar', 'price_per_unit', 'unit', 'stock_quantity', 'is_active'];
    }
}
