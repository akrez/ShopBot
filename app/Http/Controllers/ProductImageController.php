<?php

namespace App\Http\Controllers;

use App\DTO\GalleryDTO;
use App\Enums\Gallery\GalleryCategory;
use App\Services\BlogService;
use App\Services\GalleryService;
use App\Services\ProductService;
use App\Support\WebResponse;
use Illuminate\Http\Request;

class ProductImageController extends Controller
{
    public function __construct(
        protected BlogService $blogService,
        protected ProductService $productService,
        protected GalleryService $galleryService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(int $product_id)
    {
        $blog = $this->blogService->findOrFailActiveBlog();
        $product = $this->productService->findOrFailActiveBlogProduct($product_id);

        $productImages = $this->galleryService->getLatestQuery($blog, $product::class, $product->id, GalleryCategory::PRODUCT_IMAGE->value)->get();

        return view('product_images.index', [
            'product' => $product,
            'productImages' => $productImages,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(int $product_id)
    {
        $product = $this->productService->findOrFailActiveBlogProduct($product_id);

        return view('product_images.create', [
            'product' => $product,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, int $product_id)
    {
        $blog = $this->blogService->findOrFailActiveBlog();
        $product = $this->productService->findOrFailActiveBlogProduct($product_id);

        $response = $this->galleryService->store($blog, $product::class, $product->id, GalleryCategory::PRODUCT_IMAGE, new GalleryDTO(
            $request->file('file'),
            $request->gallery_order,
            $request->is_selected
        ));

        return new WebResponse($response, route('products.product_images.index', ['product_id' => $product_id]));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, int $product_id, string $name)
    {
        $product = $this->productService->findOrFailActiveBlogProduct($product_id);
        $gallery = $this->galleryService->findOrFailActiveBlogGallery($name);

        return view('product_images.edit', [
            'product' => $product,
            'gallery' => $gallery,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $product_id, string $name)
    {
        $blog = $this->blogService->findOrFailActiveBlog();
        $product = $this->productService->findOrFailActiveBlogProduct($product_id);
        $gallery = $this->galleryService->findOrFailActiveBlogGallery($name);

        $response = $this->galleryService->update($blog, $gallery, new GalleryDTO(
            null,
            $request->gallery_order,
            $request->is_selected
        ));

        return new WebResponse($response, route('products.product_images.index', ['product_id' => $product_id]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, int $product_id, string $name)
    {
        $blog = $this->blogService->findOrFailActiveBlog();
        $product = $this->productService->findOrFailActiveBlogProduct($product_id);
        $gallery = $this->galleryService->findOrFailActiveBlogGallery($name);

        $response = $this->galleryService->destroy($blog, $gallery);

        return new WebResponse($response, route('products.product_images.index', ['product_id' => $product_id]));
    }
}
