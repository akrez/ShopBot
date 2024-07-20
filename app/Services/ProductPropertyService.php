<?php

namespace App\Services;

use App\DTO\ProductPropertyDTO;
use App\Facades\ArrayHelper;
use App\Models\Blog;
use App\Models\Product;

class ProductPropertyService
{
    const MAX_LENGTH = 32;

    const SEPARATOR_LINES = [PHP_EOL];

    const SEPARATOR_KEY_VALUES = [':', ',', '،', "\t"];

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

    public function getTextAreaInputString(Product $product)
    {
        $metas = $this->getLatestProductProperties($product);

        $keyValues = [];
        foreach ($metas as $meta) {
            $keyValues[$meta->property_key][$meta->property_value] = $meta->property_value;
        }

        $keyValue = [];
        foreach ($keyValues as $key => $values) {
            $keyValue[$key] = implode(static::GLUE_VALUES, $values);
        }

        $lines = [];
        foreach ($keyValue as $key => $values) {
            $lines[] = $key . static::GLUE_KEY_VALUES . $values;
        }

        return implode(static::GLUE_LINES, $lines);
    }

    public function export(Blog $blog)
    {
        $source = [];

        $source[] = [
            __('validation.attributes.code'),
            __('validation.attributes.name'),
            __('validation.attributes.property_key'),
            __('validation.attributes.property_value'),
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

    public function getSubArrayGrouped($rows, $devideIndex, $groupByIndex = 0)
    {
        $result = [];
        foreach ($rows as $row) {
            if (is_array($row)) {
                $rowAsString = collect($row)->flatten()->implode(static::GLUE_VALUES);
            } else {
                $rowAsString = strval($row);
            }
            //
            $rowAsArray = ArrayHelper::iexplode(static::SEPARATOR_KEY_VALUES, $rowAsString);
            $rowAsArray += array_fill(0, $devideIndex + 1, null);
            //
            $groupKey = trim($rowAsArray[$groupByIndex]);
            //
            $result[$groupKey][] = array_slice($rowAsArray, $devideIndex);
        }
        //
        return $result;
    }

    public function import(Blog $blog, array $rows)
    {
        $rows = $rows + [0 => []];
        unset($rows[0]);

        $productsKeysValues = $this->getSubArrayGrouped($rows, 2, 0);
        //
        foreach ($productsKeysValues as $productCode => $lines) {
            $product = resolve(ProductService::class)->firstProductByCode($blog, $productCode);
            if ($product) {
                $this->syncProduct($blog, $product, $lines);
            }
        }
    }

    public function syncProduct(Blog $blog, Product $product, array $lines)
    {
        $this->delete($product);
        $dtos = $this->filter($lines);
        $data = $this->insert($blog, $product, $dtos);

        return $data;
    }

    public function delete(Product $product)
    {
        $product->productProperties()->delete();
    }

    public function filter(array $lines)
    {
        $keyValues = $this->getSubArrayGrouped($lines, 1, 0);

        $dtos = [];
        foreach ($keyValues as $key => $values) {
            $safeValues = collect($values)
                ->flatten()
                ->map(fn ($value) => trim($value))
                ->filter()
                ->unique()
                ->toArray();
            foreach ($safeValues as $safeValue) {
                $dto = new ProductPropertyDTO($key, $safeValue);
                if ($dto->validate()->errors()->isEmpty()) {
                    $dtos[] = $dto;
                }
            }
        }

        //
        return $dtos;
    }

    /**
     * @param  array<int, ProductPropertyDTO>  $dtos
     */
    public function insert(Blog $blog, Product $product, array $productPropertyDtos): array
    {
        $result = [];
        foreach ($productPropertyDtos as $productPropertyDto) {
            $result[] = $blog->productProperties()->create([
                'property_key' => $productPropertyDto->property_key,
                'property_value' => $productPropertyDto->property_value,
                'product_id' => $product->id,
            ])->toArray();
        }

        return $result;
    }
}
