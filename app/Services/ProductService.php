<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\Product;

class ProductService
{
    public function getLatestBlogProducts(Blog $blog)
    {
        return $blog->products()->latest('created_at')->get();
    }

    public function create(Blog $blog, array $data)
    {
        $blog->products()->create($data);
    }

    public function update(Product $product, array $data)
    {
        $product->update($data);
    }

    public function findOrFailActiveBlogProduct($productId)
    {
        $blog = resolve(BlogService::class)->findOrFailActiveBlog();
        $product = $blog->products()->where('id', $productId)->first();
        abort_unless($blog and $product, 404);

        return $product;
    }
}
