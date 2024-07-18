<?php

namespace App\Services;

use App\DTO\ProductTagDTO;
use App\Facades\ResponseBuilder;
use App\Models\Blog;
use App\Models\Product;

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

    public function firstOrCreate(Blog $blog, Product $product, ProductTagDTO $productTagDTO)
    {
        $validation = $productTagDTO->validate();

        if ($validation->errors()->isNotEmpty()) {
            return ResponseBuilder::status(402)->errors($validation->errors()->toArray());
        }

        $tag = $blog->productTags()->firstOrCreate([
            'tag_name' => $productTagDTO->name,
            'blog_id' => $blog->id,
        ], [
            'product_id' => $product->id,
        ]);

        if ($tag and $tag->name) {
            return ResponseBuilder::status($tag->wasRecentlyCreated ? 201 : 200)->data($tag->toArray());
        }

        return ResponseBuilder::status(500);
    }

    public function syncProductTags(Blog $blog, Product $product, array $tags, bool $deleteOld = true)
    {
        if ($deleteOld) {
            $product->productTags()->delete();
        }

        foreach ($tags as $tag) {
            if (! $tag) {
                continue;
            }

            $tagResponse = $this->firstOrCreate($blog, $product, new ProductTagDTO($tag));
            if ($tagResponse->isSuccessful()) {
                $validTags[] = $tagResponse->getData()['name'];
            }
        }
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

    public function import(Blog $blog, array $rows)
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
                $this->syncProductTags($blog, $product, array_slice($row, 2));
            }
        }
    }
}
