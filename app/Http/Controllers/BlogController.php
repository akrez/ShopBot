<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Models\Blog;
use App\Models\Visit;
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
            'blogs' => Auth::user()->blogs()->orderBy('created_at', 'desc')->get(),
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
        $blog = new Blog($request->validated());
        $blog->name = $request->name;
        $blog->created_by = Auth::id();
        $blog->save();

        return redirect()
            ->route('blogs.index')
            ->with('success', 'sdfsdgsfhdf dfh dfhdfhdf hdhdf hdfhdfh');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $visits = Visit::filterBlogName($blog->name)
            ->orderDefault()
            ->paginate(250);

        return view('blogs.show', [
            'blog' => $blog,
            'visits' => $visits,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        return view('blogs.edit', [
            'blog' => $blog,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBlogRequest $request, Blog $blog)
    {
        $blog->update($request->validated());
        $blog->save();

        return redirect()
            ->route('blogs.index')
            ->with('success', 'sdfsdgsfhdf dfh dfhdfhdf hdhdf hdfhdfh');
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
        $blog = $user->blogs()->where('id', $id)->first();

        abort_unless($user and $blog, 404);

        $user->active_blog = $blog->id;
        $user->save();

        return redirect()->route('blogs.index');
    }
}
