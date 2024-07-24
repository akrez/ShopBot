<?php

namespace App\Services;

use App\DTO\ProductTagDTO;
use App\Facades\ArrayHelper;
use App\Facades\ResponseBuilder;
use App\Models\Blog;
use App\Models\Product;
use App\Support\ArrayHelper as SupportArrayHelper;

class ProductTagService
{
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

    public function delete(Product $product)
    {
        $product->productTags()->delete();
    }

    public function filter(array $tags)
    {
        $stringLine = implode(SupportArrayHelper::GLUE_VALUES, collect($tags)->flatten()->toArray());

        $tags = ArrayHelper::iexplode(SupportArrayHelper::SEPARATOR_KEY_VALUES, $stringLine);

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

    public function insert(Blog $blog, Product $product, array $tags)
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

    public function importFromTextArea(Blog $blog, Product $product, ?string $content)
    {
        return $this->import($blog, $product, explode(SupportArrayHelper::GLUE_LINES, $content));
    }

    private function import(Blog $blog, Product $product, array $tags)
    {
        $this->delete($product);
        $safeTags = $this->filter($tags);
        $data = $this->insert($blog, $product, $safeTags);

        if (count($safeTags) == count($data)) {
            if (count($safeTags) == 0) {
                return ResponseBuilder::status(201)->data($data)->message(__('All :names removed', [
                    'names' => __('Tags'),
                ]));
            }

            return ResponseBuilder::status(201)->data($data)->message(__(':count :names are created successfully', [
                'count' => count($safeTags),
                'names' => __('Tag'),
            ]));
        }

        return ResponseBuilder::status(500);
    }

    public function export(Blog $blog)
    {
        $source = [];

        $source[] = [
            __('validation.attributes.code'),
            __('validation.attributes.name'),
        ];

        $products = $this->getLatestProductsWithTags($blog);
        foreach ($products as $product) {
            $row = [
                $product->code,
                $product->name,
            ];
            foreach ($product->productTags as $productTag) {
                $row[] = $productTag->tag_name;
            }
            $source[] = $row;
        }

        return $source;
    }

    public function importFromExcel(Blog $blog, array $rows)
    {
        $skipedRow = 0;
        foreach ($rows as $row) {
            if ($skipedRow < 1) {
                $skipedRow++;

                continue;
            }
            //
            $row = ((array) $row) + array_fill(0, 2, null);
            //
            $product = resolve(ProductService::class)->firstProductByCode($blog, $row[0]);
            //
            if ($product) {
                $this->import($blog, $product, array_slice($row, 2));
            }
        }
    }
}
