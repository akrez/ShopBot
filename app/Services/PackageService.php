<?php

namespace App\Services;

use App\Contracts\PortContract;
use App\DTO\PackageDTO;
use App\Models\Blog;
use App\Models\Package;
use App\Models\Product;
use App\Support\ResponseBuilder;

class PackageService //implements PortContract
{
    public function getLatestProductsWithPackages(Blog $blog)
    {
        $blog->load([
            'products' => function ($query) {
                $query->with('packages', function ($query) {
                    $query->latest('created_at');
                })->latest('created_at');
            },
        ]);

        return $blog->products;
    }

    public function getLatestPackages(Product $product): \Illuminate\Database\Eloquent\Collection
    {
        $product->load([
            'packages' => function ($query) {
                $query->latest('created_at');
            },
        ]);

        return $product->packages;
    }

    public function findOrFailActiveBlogPackage(Product $product, $packageId)
    {
        $package = $product->packages()->where('id', $packageId)->first();
        abort_unless($package, 404);

        return $package;
    }

    public function store(Product $product, PackageDTO $packageDTO)
    {
        $blog = $product->blog;

        $responseBuilder = resolve(ResponseBuilder::class)->input($packageDTO);

        $validation = $packageDTO->validate(true, ['blog' => $blog]);

        if ($validation->errors()->isNotEmpty()) {
            return $responseBuilder->status(422)->message('Unprocessable Entity')->errors($validation->errors());
        }

        $package = new Package;
        $package->fill($packageDTO->data());
        $package->blog_id = $blog->id;
        $package->product_id = $product->id;
        if (! $package->save()) {
            return $responseBuilder->status(500)->message('Internal Server Error');
        }

        return $responseBuilder->status(201)->data($package)->message(__(':name is created successfully', [
            'name' => __('Package'),
        ]));
    }

    public function update(Product $product, Package $package, PackageDTO $packageDTO)
    {
        $responseBuilder = resolve(ResponseBuilder::class)->input($packageDTO);

        $validation = $packageDTO->validate(false, ['blog' => $product->blog]);

        if ($validation->errors()->isNotEmpty()) {
            return $responseBuilder->status(422)->message('Unprocessable Entity')->errors($validation->errors());
        }

        $isSuccessful = $package->update($packageDTO->data());
        if (! $isSuccessful) {
            return $responseBuilder->status(500)->message('Internal Server Error');
        }

        return $responseBuilder->data($package)->status(200)->message(__(':name is updated successfully', [
            'name' => __('Package'),
        ]));
    }

    public function destroy(Package $package)
    {
        if (! $package->delete()) {
            return ResponseBuilder::new(500)->message('Internal Server Error');
        }

        return ResponseBuilder::new(200)->message(__(':name is deleted successfully', [
            'name' => __('Package'),
        ]));
    }
}
