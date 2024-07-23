<?php

namespace App\Http\Controllers;

use App\DTO\ProductDTO;
use App\Enums\Gallery\GalleryCategory;
use App\Services\BlogService;
use App\Services\ProductService;
use App\Support\WebResponse;
use Illuminate\Http\Request;

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
            'products' => $this->productService->getLatestBlogProductsQuery($blog)
                ->with(['images' => function ($query) {
                    return $query->where('gallery_category', GalleryCategory::PRODUCT_IMAGE->value);
                }])->get(),
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
    public function store(Request $request)
    {
        $blog = $this->blogService->findOrFailActiveBlog();

        $response = $this->productService->store($blog, new ProductDTO(
            $request->code,
            $request->name,
            $request->product_status
        ));

        return new WebResponse($response, route('products.index'));
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
    public function update(Request $request, int $id)
    {
        $blog = $this->blogService->findOrFailActiveBlog();
        $product = $this->productService->findOrFailActiveBlogProduct($id);

        $response = $this->productService->update($blog, $product, new ProductDTO(
            $request->code,
            $request->name,
            $request->product_status
        ));

        return new WebResponse($response, route('products.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        //
    }
}
