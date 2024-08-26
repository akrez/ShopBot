<?php

namespace App\Http\Controllers;

use App\DTO\ColorDTO;
use App\Services\BlogService;
use App\Services\ColorService;
use App\Support\WebResponse;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function __construct(
        protected BlogService $blogService,
        protected ColorService $colorService,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blog = $this->blogService->findOrFailActiveBlog();

        return view('colors.index', [
            'colors' => $this->colorService->getLatestBlogColorsQuery($blog)->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('colors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $blog = $this->blogService->findOrFailActiveBlog();

        $response = $this->colorService->store($blog, new ColorDTO(
            $request->code,
            $request->name
        ));

        return new WebResponse($response, route('colors.index'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $code)
    {
        $color = $this->colorService->findOrFailActiveBlogColor($code);

        return view('colors.edit', [
            'color' => $color,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $code)
    {
        $blog = $this->blogService->findOrFailActiveBlog();
        $color = $this->colorService->findOrFailActiveBlogColor($code);

        $response = $this->colorService->update($blog, $color, new ColorDTO(
            $request->code,
            $request->name
        ));

        return new WebResponse($response, route('colors.index'));
    }
}
