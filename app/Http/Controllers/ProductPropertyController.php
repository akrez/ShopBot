<?php

namespace App\Http\Controllers;

use App\Services\BlogService;
use App\Services\ProductPropertyService;
use App\Services\ProductService;
use App\Support\WebResponse;
use Illuminate\Http\Request;

class ProductPropertyController extends Controller
{
    public function __construct(
        protected BlogService $blogService,
        protected ProductService $productService,
        protected ProductPropertyService $productPropertyService
    ) {}

    /**
     * Show the form for creating a new resource.
     */
    public function create(int $product_id)
    {
        $product = $this->productService->findOrFailActiveBlogProduct($product_id);

        return view('product_properties.create', [
            'product' => $product,
            'productProperties' => $this->productPropertyService->getLatestProductProperties($product),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, int $product_id)
    {
        $blog = $this->blogService->findOrFailActiveBlog();
        $product = $this->productService->findOrFailActiveBlogProduct($product_id);

        $response = $this->productPropertyService->syncProducts($blog, $product, explode("\n", $request->property_names));

        return new WebResponse($response);
    }
}
