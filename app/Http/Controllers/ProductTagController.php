<?php

namespace App\Http\Controllers;

use App\Services\BlogService;
use App\Services\ProductService;
use App\Services\ProductTagService;
use App\Support\WebResponse;
use Illuminate\Http\Request;

class ProductTagController extends Controller
{
    public function __construct(
        protected BlogService $blogService,
        protected ProductService $productService,
        protected ProductTagService $productTagService
    ) {}

    /**
     * Show the form for creating a new resource.
     */
    public function create(int $product_id)
    {
        $product = $this->productService->findOrFailActiveBlogProduct($product_id);

        return view('product_tags.create', [
            'product' => $product,
            'productTags' => $this->productTagService->getLatestProductTags($product),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, int $product_id)
    {
        $blog = $this->blogService->findOrFailActiveBlog();
        $product = $this->productService->findOrFailActiveBlogProduct($product_id);

        $response = $this->productTagService->sync($blog, $product, explode("\n", $request->tag_names));

        return new WebResponse($response);
    }
}
