<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Models\Blog;
use App\Services\BlogService;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('blogs.index', [
            'blogs' => (new BlogService())->getLatestUserBlogs(Auth::user()),
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
        (new BlogService())->create(Auth::user(), $request->validated());

        return redirect()->route('blogs.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        $blog = (new BlogService())->getUserBlog(Auth::user(), $id);

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
        $blog = (new BlogService())->getUserBlog(Auth::user(), $id);

        (new BlogService())->update($blog, $request->validated());

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
        $blog = (new BlogService())->getUserBlog($user, $id);

        (new BlogService())->setUserActiveBlog($user, $blog);

        return redirect()->route('blogs.index');
    }
}
