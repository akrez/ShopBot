<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Models\Blog;
use App\Services\BlogService;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    public function __construct(protected BlogService $blogService) {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('blogs.index', [
            'blogs' => $this->blogService->getLatestUserBlogs(Auth::user()),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('blogs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    // public function store(HttpRequest $request)
    public function store(StoreBlogRequest $request)
    {
        $this->blogService->create(Auth::user(), $request->validated());

        return redirect()->route('blogs.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        $blog = $this->blogService->findOrFailUserBlog(Auth::user(), $id);

        return view('blogs.edit', [
            'blog' => $blog,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBlogRequest $request, int $id)
    {
        $blog = $this->blogService->findOrFailUserBlog(Auth::user(), $id);

        $this->blogService->update($blog, $request->validated());

        return redirect()->route('blogs.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blog $blog)
    {
        //
    }

    public function active(int $id)
    {
        $user = Auth::user();
        $blog = $this->blogService->findOrFailUserBlog($user, $id);

        $this->blogService->setUserActiveBlog($user, $blog);

        return redirect()->route('blogs.index');
    }
}
