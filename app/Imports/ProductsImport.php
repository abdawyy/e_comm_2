<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{
    public function model(array $row): ?Product
    {
        if (blank($row['sku'] ?? null)) {
            return null;
        }

        return Product::query()->updateOrCreate(
            ['sku' => $row['sku']],
            [
                'name' => ['en' => $row['name_en'] ?? $row['sku'], 'ar' => $row['name_ar'] ?? $row['name_en'] ?? $row['sku']],
                'price_per_unit' => $row['price_per_unit'] ?? '0',
                'unit' => $row['unit'] ?? 'piece',
                'stock_quantity' => $row['stock_quantity'] ?? '0',
                'track_stock' => true,
                'is_active' => (bool) ($row['is_active'] ?? true),
            ],
        );
    }
}
