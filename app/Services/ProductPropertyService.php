<?php

namespace App\Services;

use App\DTO\ProductPropertyDTO;
use App\Models\Blog;
use App\Models\Product;

class ProductPropertyService
{
    const MAX_LENGTH = 32;

    const SEPARATOR_LINES = [PHP_EOL];

    const SEPARATOR_KEY_VALUES = [':', "\t"];

    const SEPARATOR_VALUES = [',', '،', "\t"];

    const GLUE_LINES = PHP_EOL;

    const GLUE_KEY_VALUES = ':';

    const GLUE_VALUES = ',';

    public function getLatestProductsWithProperties(Blog $blog)
    {
        $blog->load([
            'products' => function ($query) {
                $query->with('productProperties', function ($query) {
                    $query->latest('created_at');
                })->latest('created_at');
            },
        ]);

        return $blog->products;
    }

    public function getLatestProductProperties(Product $product): \Illuminate\Database\Eloquent\Collection
    {
        $product->load([
            'productProperties' => function ($query) {
                $query->latest('created_at');
            },
        ]);

        return $product->productProperties;
    }

    public function delete(Product $product)
    {
        $product->productProperties()->delete();
    }

    public function filter(array $keyValueLines)
    {
        $keyValues = [];
        foreach ($keyValueLines as $keyValueLine) {
            $keyValueLine = (array) $keyValueLine;
            $keyValueLine += array_fill(0, 1, null);
            //
            $key = trim($keyValueLine[0]);
            //
            $keyValues[$key][] = array_slice($keyValueLine, 1);
        }

        $safeKeyValues = [];
        foreach ($keyValues as $key => $values) {
            $safeKeyValues[$key] = collect($values)
                ->flatten()
                ->map(fn ($value) => trim($value))
                ->filter()
                ->unique()
                ->filter(fn ($tag) => (new ProductPropertyDTO($key, $tag))
                    ->validate()
                    ->errors()
                    ->isEmpty())
                ->toArray();
        }

        //
        return $safeKeyValues;
    }

    public function insert(Blog $blog, Product $product, array $safeKeyValues)
    {
        $data = [];
        foreach ($safeKeyValues as $safeKey => $safeValues) {
            foreach ($safeValues as $safeValue) {
                $data[] = $blog->productProperties()->create([
                    'property_key' => $safeKey,
                    'property_value' => $safeValue,
                    'product_id' => $product->id,
                ])->toArray();
            }
        }

        return $data;
    }

    public function syncProduct(Blog $blog, Product $product, array $keysValues)
    {
        $this->delete($product);
        $safeProperties = $this->filter($keysValues);
        $data = $this->insert($blog, $product, $safeProperties);
    }

    public function export(Blog $blog)
    {
        $source = [];

        $source[] = [
            __('validation.attributes.code'),
            __('validation.attributes.name'),
            __('validation.attributes.property_key'),
        ];

        $products = $this->getLatestProductsWithProperties($blog);
        foreach ($products as $product) {
            $properties = $product->productProperties()->get()->groupBy('property_key');
            if ($properties->isEmpty()) {
                $source[] = [
                    $product->code,
                    $product->name,
                ];
            } else {
                foreach ($properties as $propertyKey => $property) {
                    $source[] = [
                        $product->code,
                        $product->name,
                        $propertyKey,
                        ...$property->pluck('property_value')->toArray(),
                    ];
                }
            }
        }

        return $source;
    }

    public function import(Blog $blog, array $rows)
    {
        $rows = $rows + [0 => []];
        unset($rows[0]);

        $productsKeysValues = [];
        foreach ($rows as $row) {
            $row = (array) $row;
            $row += array_fill(0, 3, null);
            //
            $code = trim($row[0]);
            //
            $productsKeysValues[$code][] = array_slice($row, 2);
        }
        //
        foreach ($productsKeysValues as $productCode => $keyValueLines) {
            $product = resolve(ProductService::class)->firstProductByCode($blog, $productCode);
            if ($product) {
                $this->syncProduct($blog, $product, $keyValueLines);
            }
        }
    }
}
