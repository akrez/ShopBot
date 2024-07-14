<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Services\BlogService;
use App\Services\ProductService;

class ProductController extends Controller
{
    public function __construct(
        protected BlogService $blogService,
        protected ProductService $productService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blog = $this->blogService->findOrFailActiveBlog();

        return view('products.index', [
            'products' => $this->productService->getLatestBlogProducts($blog),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $blog = $this->blogService->findOrFailActiveBlog();

        $this->productService->create($blog, $request->validated());

        return redirect()->route('products.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $product = $this->productService->findOrFailActiveBlogProduct($id);

        return view('products.edit', [
            'product' => $product,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, int $id)
    {
        $product = $this->productService->findOrFailActiveBlogProduct($id);

        $this->productService->update($product, $request->validated());

        return redirect()->route('products.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        //
    }
}
