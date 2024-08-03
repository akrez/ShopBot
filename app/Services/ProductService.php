<?php

namespace App\Services;

use App\Contracts\PortContract;
use App\DTO\ProductDTO;
use App\Models\Blog;
use App\Models\Product;
use App\Support\ResponseBuilder;

class ProductService implements PortContract
{
    public function getLatestBlogProductsQuery(Blog $blog)
    {
        return $blog->products()->orderDefault();
    }

    public function store(Blog $blog, ProductDTO $productDto)
    {
        $responseBuilder = resolve(ResponseBuilder::class)->input($productDto);

        $validation = $productDto->validate(true, [
            'blog' => $blog,
        ]);

        if ($validation->errors()->isNotEmpty()) {
            return $responseBuilder->status(422)->message('Unprocessable Entity')->errors($validation->errors());
        }

        $product = $blog->products()->create($productDto->data());

        if (! $product) {
            return $responseBuilder->status(500)->message('Internal Server Error');
        }

        return $responseBuilder->status(201)->data($product)->message(__(':name is created successfully', [
            'name' => __('Product'),
        ]));
    }

    public function update(Blog $blog, Product $product, ProductDTO $productDto)
    {
        $responseBuilder = resolve(ResponseBuilder::class)->input($productDto);

        $validation = $productDto->validate(false, [
            'blog' => $blog,
            'id' => $product->id,
        ]);

        if ($validation->errors()->isNotEmpty()) {
            return $responseBuilder->status(422)->message('Unprocessable Entity')->errors($validation->errors());
        }

        $isSuccessful = $product->update($productDto->data());

        if (! $isSuccessful) {
            return $responseBuilder->status(500)->message('Internal Server Error');
        }

        return $responseBuilder->status(200)->data($product)->message(__(':name is updated successfully', [
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

    public function exportToExcel(Blog $blog)
    {
        $source = [];

        $source[] = [
            __('validation.attributes.code'),
            __('validation.attributes.name'),
            __('validation.attributes.status'),
            __('validation.attributes.product_order'),
        ];

        foreach ($this->getLatestBlogProductsQuery($blog)->get() as $product) {
            $source[] = [
                $product->code,
                $product->name,
                $product->product_status->value,
                $product->product_order,
            ];
        }

        return $source;
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
            $row = ((array) $row) + array_fill(0, 3, null);
            $productDTO = new ProductDTO($row[0], $row[1], $row[2], $row[3]);
            //
            $product = $this->firstProductByCode($blog, $productDTO->code);
            //
            if ($product) {
                $result[] = $this->update($blog, $product, $productDTO);
            } else {
                $result[] = $this->store($blog, $productDTO);
            }
        }

        return $result;
    }
}
