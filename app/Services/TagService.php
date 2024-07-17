<?php

namespace App\Services;

use App\Models\Blog;

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
}
