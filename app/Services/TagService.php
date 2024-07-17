<?php

namespace App\Services;

use App\DTO\TagDTO;
use App\Facades\ResponseBuilder;
use App\Models\Blog;
use App\Models\Product;

class TagService
{
    public function getLatestProductsWithTags(Blog $blog)
    {
        $blog->load([
            'products' => function ($query) {
                $query->with('tags', function ($query) {
                    $query->latest('created_at');
                })->latest('created_at');
            },
        ]);

        return $blog->products;
    }

    public function firstOrCreate(Blog $blog, TagDTO $tagDto)
    {
        $validation = $tagDto->validate();

        if ($validation->errors()->isNotEmpty()) {
            return ResponseBuilder::status(402)->errors($validation->errors()->toArray());
        }

        $tag = $blog->tags()->firstOrCreate([
            'name' => $tagDto->name,
        ]);

        if ($tag and $tag->name) {
            return ResponseBuilder::status($tag->wasRecentlyCreated ? 201 : 200)->data($tag->toArray());
        }

        return ResponseBuilder::status(500);
    }

    public function syncProductTags(Blog $blog, Product $product, array $tags)
    {
        $validTags = [];
        foreach ($tags as $tag) {
            if (!$tag) {
                continue;
            }

            $tagResponse = $this->firstOrCreate($blog, new TagDTO($tag));
            if ($tagResponse->isSuccessful()) {
                $validTags[] = $tagResponse->getData()['name'];
            }
        }

        $product->tags()->sync($validTags, false);
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
            foreach ($product->tags as $tag) {
                $row[] = $tag->name;
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
