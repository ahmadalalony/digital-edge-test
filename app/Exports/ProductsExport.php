<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(private array $filters = []) {}

    public function query()
    {
        $q = Product::query()->with('creator');

        $search = data_get($this->filters, 'search.value');
        if ($search) {
            $q->where(function ($sub) use ($search) {
                $sub->where('title_en', 'like', "%{$search}%")
                    ->orWhere('title_ar', 'like', "%{$search}%")
                    ->orWhere('description_en', 'like', "%{$search}%")
                    ->orWhere('description_ar', 'like', "%{$search}%");
            });
        }

        return $q->orderByDesc('id');
    }

    public function headings(): array
    {
        return ['ID', 'Title EN', 'Title AR', 'Price', 'Created By'];
    }

    public function map($product): array
    {
        return [
            $product->id,
            $product->title_en,
            $product->title_ar,
            $product->price,
            optional($product->creator)->full_name,
        ];
    }
}
