<?php

namespace App\Services;

use App\DTO\ProductPropertyDTO;
use App\Facades\ArrayHelper;
use App\Facades\ResponseBuilder;
use App\Models\Blog;
use App\Models\Product;

class ProductPropertyService
{
    const PROPERTY_MAX_LENGTH = 32;

    const KEY_VALUES_SEPARATORS = [
        ':' => ':',
        ',' => ',',
        '،' => '،',
        "\t" => 'Tab',
    ];

    const KEY_VALUES_GLUE = ':';

    const VALUES_GLUE = ',';

    const LINES_SEPARATORS = [
        PHP_EOL => 'Enter',
    ];

    const LINES_GLUE = PHP_EOL;

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

    public function exportToTextArea(Product $product)
    {
        $properties = $this->getLatestProductProperties($product);

        $keyToValues = [];
        foreach ($properties as $property) {
            $keyToValues[$property->property_key][$property->property_value] = $property->property_value;
        }

        $lines = [];
        foreach ($keyToValues as $key => $values) {
            $lines[] = $key.ProductPropertyService::KEY_VALUES_GLUE.' '.implode(ProductPropertyService::VALUES_GLUE.' ', $values);
        }

        return implode(ProductPropertyService::LINES_GLUE, $lines);
    }

    public function exportToExcel(Blog $blog)
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

    public function importFromExcel(Blog $blog, array $rows)
    {
        $result = [];
        //
        $rows = $rows + [0 => []];
        unset($rows[0]);
        //
        $stringLinesArrays = [];
        foreach ($rows as $row) {
            $row += array_fill(0, 3, '');
            //
            $productCode = trim($row[0]);
            $stringLinesArrays[$productCode][] = array_slice($row, 2);
        }
        //
        foreach ($stringLinesArrays as $productCode => $keyAndValuesArray) {
            $product = resolve(ProductService::class)->firstProductByCode($blog, $productCode);
            if ($product) {
                $result[] = $this->importfromArray($blog, $product, $keyAndValuesArray);
            }
        }

        return $result;
    }

    public function importFromTextArea(Blog $blog, Product $product, ?string $content)
    {
        $keyAndValuesArray = [];
        $stringLines = ArrayHelper::iexplode(array_keys(ProductPropertyService::LINES_SEPARATORS), $content);
        foreach ($stringLines as $stringLine) {
            $keyAndValuesArray[] = ArrayHelper::iexplode(array_keys(ProductPropertyService::KEY_VALUES_SEPARATORS), $stringLine);
        }

        return $this->importfromArray($blog, $product, $keyAndValuesArray);
    }

    public function importfromArray(Blog $blog, Product $product, array $keyAndValuesArray)
    {
        $keyToValuesArray = [];
        foreach ($keyAndValuesArray as $keyAndValues) {
            $keyAndValues += array_fill(0, 2, '');
            //
            $key = trim($keyAndValues[0]);
            //
            if (! array_key_exists($key, $keyToValuesArray)) {
                $keyToValuesArray[$key] = [];
            }
            //
            $keyToValuesArray[$key] = array_merge($keyToValuesArray[$key], array_slice($keyAndValues, 1));
        }

        return $this->import($blog, $product, $keyToValuesArray);
    }

    protected function import(Blog $blog, Product $product, array $keyToValuesArray)
    {
        $this->delete($product);
        $dtos = $this->filter($keyToValuesArray);
        $data = $this->insert($blog, $product, $dtos);

        if (count($dtos) == count($data)) {
            if (count($dtos) == 0) {
                return ResponseBuilder::status(201)->data($data)->message(__('All :names removed', [
                    'names' => __('Property'),
                ]));
            }

            return ResponseBuilder::status(201)->data($data)->message(__(':count :names are created successfully', [
                'count' => count($dtos),
                'names' => __('Property'),
            ]));
        }

        return ResponseBuilder::status(500);
    }

    protected function delete(Product $product)
    {
        $product->productProperties()->delete();
    }

    protected function filter(array $keyToValuesArray)
    {
        $dtos = [];
        foreach ($keyToValuesArray as $key => $values) {
            $keyToValuesArray[$key] = collect($values)
                ->flatten()
                ->map(fn ($value) => trim($value))
                ->filter()
                ->unique()
                ->toArray();
            foreach ($keyToValuesArray[$key] as $safeValue) {
                $dto = new ProductPropertyDTO($key, $safeValue);
                if ($dto->validate()->errors()->isEmpty()) {
                    $dtos[] = $dto;
                }
            }
        }

        return $dtos;
    }

    /**
     * @param  array<int, ProductPropertyDTO>  $dtos
     */
    protected function insert(Blog $blog, Product $product, array $productPropertyDtos): array
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
