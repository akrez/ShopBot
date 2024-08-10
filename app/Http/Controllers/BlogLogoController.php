<?php

namespace App\Http\Controllers;

use App\DTO\GalleryDTO;
use App\Enums\Gallery\GalleryCategory;
use App\Services\BlogService;
use App\Services\GalleryService;
use App\Support\WebResponse;
use Illuminate\Http\Request;

class BlogLogoController extends Controller
{
    public function __construct(
        protected BlogService $blogService,
        protected GalleryService $galleryService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blog = $this->blogService->findOrFailActiveBlog();

        $blogLogos = $this->galleryService->getLatestQuery($blog, $blog::class, $blog->id, GalleryCategory::BLOG_LOGO->value)->get();

        return view('blog_logos.index', [
            'blogLogos' => $blogLogos,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('blog_logos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $blog = $this->blogService->findOrFailActiveBlog();

        $response = $this->galleryService->store($blog, $blog::class, $blog->id, GalleryCategory::BLOG_LOGO, new GalleryDTO(
            $request->file('file'),
            $request->gallery_order,
            $request->is_selected
        ));

        return new WebResponse($response, route('blog_logos.index'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $name)
    {
        $gallery = $this->galleryService->findOrFailActiveBlogGallery($name);

        return view('blog_logos.edit', [
            'gallery' => $gallery,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $name)
    {
        $blog = $this->blogService->findOrFailActiveBlog();
        $gallery = $this->galleryService->findOrFailActiveBlogGallery($name);

        $response = $this->galleryService->update($blog, $gallery, new GalleryDTO(
            null,
            $request->gallery_order,
            $request->is_selected
        ));

        return new WebResponse($response, route('blog_logos.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $name)
    {
        $blog = $this->blogService->findOrFailActiveBlog();
        $gallery = $this->galleryService->findOrFailActiveBlogGallery($name);

        $response = $this->galleryService->destroy($blog, $gallery);

        return new WebResponse($response, route('blog_logos.index'));
    }
}
