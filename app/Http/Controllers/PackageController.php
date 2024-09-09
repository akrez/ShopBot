<?php

namespace App\Http\Controllers;

use App\DTO\PackageDTO;
use App\Services\BlogService;
use App\Services\ColorService;
use App\Services\PackageService;
use App\Services\ProductService;
use App\Support\WebResponse;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function __construct(
        protected BlogService $blogService,
        protected ProductService $productService,
        protected ColorService $colorService,
        protected PackageService $packageService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(int $product_id)
    {
        $product = $this->productService->findOrFailActiveBlogProduct($product_id);

        return view('packages.index', [
            'product' => $product,
            'packages' => $this->packageService->getLatestPackages($product),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(int $product_id)
    {
        $blog = $this->blogService->findOrFailActiveBlog();
        $product = $this->productService->findOrFailActiveBlogProduct($product_id);

        return view('packages.create', [
            'product' => $product,
            'colors' => $this->colorService->getLatestBlogColorsIdNameArray($blog),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, int $product_id)
    {
        $product = $this->productService->findOrFailActiveBlogProduct($product_id);

        $response = $this->packageService->store($product, new PackageDTO(
            $request->price,
            $request->package_status,
            $request->color_id,
            $request->guaranty,
            $request->description
        ));

        return new WebResponse($response, route('products.packages.index', [
            'product_id' => $product->id,
        ]));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, int $product_id, int $id)
    {
        $blog = $this->blogService->findOrFailActiveBlog();
        $product = $this->productService->findOrFailActiveBlogProduct($product_id);
        $package = $this->packageService->findOrFailActiveBlogPackage($product, $id);

        return view('packages.edit', [
            'product' => $product,
            'package' => $package,
            'colors' => $this->colorService->getLatestBlogColorsIdNameArray($blog),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $product_id, int $id)
    {
        $product = $this->productService->findOrFailActiveBlogProduct($product_id);
        $package = $this->packageService->findOrFailActiveBlogPackage($product, $id);

        $response = $this->packageService->update($product, $package, new PackageDTO(
            $request->price,
            $request->package_status,
            $request->color_id,
            $request->guaranty,
            $request->description
        ));

        return new WebResponse($response, route('products.packages.index', [
            'product_id' => $product->id,
        ]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, int $product_id, int $id)
    {
        $product = $this->productService->findOrFailActiveBlogProduct($product_id);
        $package = $this->packageService->findOrFailActiveBlogPackage($product, $id);

        $response = $this->packageService->destroy($package);

        return new WebResponse($response, route('products.packages.index', [
            'product_id' => $product->id,
        ]));
    }
}
