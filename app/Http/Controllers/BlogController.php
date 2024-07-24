<?php

namespace App\Http\Controllers;

use App\DTO\BlogDTO;
use App\Models\Blog;
use App\Services\BlogService;
use App\Support\WebResponse;
use Illuminate\Http\Request;
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
    public function store(Request $request)
    {
        $response = $this->blogService->store(Auth::user(), new BlogDTO(
            $request->name,
            $request->short_description,
            $request->description,
            $request->blog_status,
        ));

        return new WebResponse($response, route('blogs.index'));
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
    public function update(Request $request, int $id)
    {
        $blog = $this->blogService->findOrFailUserBlog(Auth::user(), $id);

        $response = $this->blogService->update($blog, new BlogDTO(
            $request->name,
            $request->short_description,
            $request->description,
            $request->blog_status,
        ));

        return new WebResponse($response, route('blogs.index'));
    }

    public function show(int $id)
    {
        $user = Auth::user();
        $blog = $this->blogService->findOrFailUserBlog($user, $id);

        return view('blogs.show', [
            'blog' => $blog,
        ]);
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

        $response = $this->blogService->setUserActiveBlog($user, $blog);

        return new WebResponse($response, route('blogs.show', ['id' => $blog->id]));
    }
}
