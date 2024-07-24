<?php

namespace App\Services;

use App\DTO\ProductDTO;
use App\Facades\ResponseBuilder;
use App\Models\Blog;
use App\Models\Product;

class ProductService
{
    public function getLatestBlogProductsQuery(Blog $blog)
    {
        return $blog->products()->latest('created_at');
    }

    public function store(Blog $blog, ProductDTO $productDto)
    {
        $validation = $productDto->validate(true, [
            'blog' => $blog,
        ]);

        if ($validation->errors()->isNotEmpty()) {
            return ResponseBuilder::status(402)->errors($validation->errors()->toArray());
        }

        $product = $blog->products()->create([
            'code' => $productDto->code,
            'name' => $productDto->name,
            'product_status' => $productDto->product_status,
        ]);

        if (! $product) {
            return ResponseBuilder::status(500)->message('Internal Server Error');
        }

        return ResponseBuilder::status(201)->data($product)->message(__(':name is created successfully', [
            'name' => __('Product'),
        ]));
    }

    public function update(Blog $blog, Product $product, ProductDTO $productDto)
    {
        $validation = $productDto->validate(false, [
            'blog' => $blog,
            'id' => $product->id,
        ]);

        if ($validation->errors()->isNotEmpty()) {
            return ResponseBuilder::data($product)->status(402)->errors($validation->errors()->toArray());
        }

        $isSuccessful = $product->update([
            'code' => $productDto->code,
            'name' => $productDto->name,
            'product_status' => $productDto->product_status,
        ]);

        if (! $isSuccessful) {
            return ResponseBuilder::status(500)->message('Internal Server Error');
        }

        return ResponseBuilder::data($product)->status(200)->message(__(':name is updated successfully', [
            'name' => __('Product'),
        ]));
    }

    public function findOrFailActiveBlogProduct($productId)
    {
        $blog = resolve(BlogService::class)->findOrFailActiveBlog();
        $product = $blog->products()->where('id', $productId)->first();
        abort_unless($product, 404);

        return $product;
    }

    public function firstProductByCode(Blog $blog, ?string $code): ?Product
    {
        if (strlen($code)) {
            return $blog->products()->where('code', $code)->first();
        }

        return null;
    }

    public function export(Blog $blog)
    {
        $source = [];

        $source[] = [
            __('validation.attributes.code'),
            __('validation.attributes.name'),
            __('validation.attributes.status'),
        ];

        foreach ($this->getLatestBlogProductsQuery($blog)->get() as $product) {
            $source[] = [
                $product->code,
                $product->name,
                $product->product_status->value,
            ];
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
            $row = ((array) $row) + array_fill(0, 3, null);
            $productDTO = new ProductDTO($row[0], $row[1], $row[2]);
            //
            $product = $this->firstProductByCode($blog, $productDTO->code);
            //
            if ($product) {
                $this->update($blog, $product, $productDTO);
            } else {
                $this->store($blog, $productDTO);
            }
        }
    }
}
