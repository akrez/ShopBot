<?php

namespace App\Services;

use App\DTO\ProductTagDTO;
use App\Facades\ArrayHelper;
use App\Models\Blog;
use App\Models\Product;
use App\Support\ResponseBuilder;

class ProductTagService
{
    const TAG_NAME_MAX_LENGTH = 32;

    const NAME_SEPARATORS = [
        PHP_EOL => 'Enter',
        ':' => ':',
        ',' => ',',
        '،' => '،',
        "\t" => 'Tab',
    ];

    const NAME_GLUE = PHP_EOL;

    public function getLatestProductsWithTags(Blog $blog)
    {
        $blog->load([
            'products' => function ($query) {
                $query->with('productTags', function ($query) {
                    $query->latest('created_at');
                })->latest('created_at');
            },
        ]);

        return $blog->products;
    }

    public function getLatestProductTags(Product $product): \Illuminate\Database\Eloquent\Collection
    {
        $product->load([
            'productTags' => function ($query) {
                $query->latest('created_at');
            },
        ]);

        return $product->productTags;
    }

    public function exportToTextArea(Product $product)
    {
        return implode(ProductTagService::NAME_GLUE, $this->exportToArray($product));
    }

    public function exportToExcel(Blog $blog)
    {
        $source = [];

        $source[] = [
            __('validation.attributes.code'),
            __('validation.attributes.name'),
            __('validation.attributes.tag_name'),
        ];

        $products = $this->getLatestProductsWithTags($blog);
        foreach ($products as $product) {
            $source[] = array_merge([
                $product->code,
                $product->name,
            ], $this->exportToArray($product));
        }

        return $source;
    }

    protected function exportToArray(Product $product)
    {
        return $this->getLatestProductTags($product)->pluck('tag_name')->toArray();
    }

    public function importFromExcel(Blog $blog, array $rows)
    {
        $result = [];
        //
        $skipedRow = 0;
        foreach ($rows as $row) {
            if ($skipedRow < 1) {
                $skipedRow++;

                continue;
            }
            //
            $row = ((array) $row) + array_fill(0, 2, null);
            //
            $productCode = trim($row[0]);
            //
            $product = resolve(ProductService::class)->firstProductByCode($blog, $productCode);
            //
            if ($product) {
                $result[$productCode] = $this->import($blog, $product, array_slice($row, 2));
            }
        }

        return $result;
    }

    public function importFromTextArea(Blog $blog, Product $product, ?string $content)
    {
        $tags = ArrayHelper::iexplode(array_keys(ProductTagService::NAME_SEPARATORS), $content);

        return $this->import($blog, $product, $tags);
    }

    protected function import(Blog $blog, Product $product, array $tags)
    {
        $this->delete($product);
        $safeTags = $this->filter($tags);
        $productTagModels = $this->insert($blog, $product, $safeTags);

        if (count($safeTags) != count($productTagModels)) {
            return resolve(ResponseBuilder::class)->status(500);
        }

        if (count($safeTags) == 0) {
            return resolve(ResponseBuilder::class)->status(200)->message(__('All :names removed', [
                'names' => __('Tags'),
            ]));
        }

        return resolve(ResponseBuilder::class)->status(201)->data($productTagModels)->message(__(':count :names are created successfully', [
            'count' => count($safeTags),
            'names' => __('Tag'),
        ]));
    }

    public function delete(Product $product)
    {
        $product->productTags()->delete();
    }

    public function filter(array $tags)
    {
        $stringLine = implode(ProductTagService::NAME_GLUE, collect($tags)->flatten()->toArray());

        $tags = ArrayHelper::iexplode(array_keys(ProductTagService::NAME_SEPARATORS), $stringLine);

        return collect($tags)
            ->map(fn ($item) => trim($item))
            ->filter()
            ->unique()
            ->filter(fn ($tag) => (new ProductTagDTO($tag))
                ->validate()
                ->errors()
                ->isEmpty())
            ->toArray();
    }

    /**
     * @param  $tags  <int, string>
     * @return <int, ProductTag>
     */
    public function insert(Blog $blog, Product $product, array $tags): array
    {
        $data = [];
        foreach ($tags as $tag) {
            $data[] = $blog->productTags()->create([
                'tag_name' => $tag,
                'product_id' => $product->id,
            ])->toArray();
        }

        return $data;
    }
}
