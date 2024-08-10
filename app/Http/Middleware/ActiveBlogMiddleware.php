<?php

namespace App\Http\Middleware;

use App\Facades\ActiveBlog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ActiveBlogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! ActiveBlog::has()) {
            return redirect()->route('blogs.index');
        }

        return $next($request);
    }
}
