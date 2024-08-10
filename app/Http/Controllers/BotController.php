<?php

namespace App\Http\Controllers;

use App\DTO\BotDTO;
use App\Services\BlogService;
use App\Services\BotService;
use App\Support\WebResponse;
use Illuminate\Http\Request;

class BotController extends Controller
{
    public function __construct(
        protected BlogService $blogService,
        protected BotService $botService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blog = $this->blogService->findOrFailActiveBlog();

        return view('bots.index', [
            'bots' => $this->botService->getLatestBlogBotsQuery($blog)->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('bots.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $blog = $this->blogService->findOrFailActiveBlog();

        $response = $this->botService->store($blog, new BotDTO(
            $request->token
        ));

        return new WebResponse($response, route('bots.index'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $bot = $this->botService->findOrFailActiveBlogBot($id);

        return view('bots.edit', [
            'bot' => $bot,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $blog = $this->blogService->findOrFailActiveBlog();
        $bot = $this->botService->findOrFailActiveBlogBot($id);

        $response = $this->botService->update($blog, $bot, new BotDTO(
            $request->token
        ));

        return new WebResponse($response, route('bots.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, int $id)
    {
        $blog = $this->blogService->findOrFailActiveBlog();
        $bot = $this->botService->findOrFailActiveBlogBot($id);

        $response = $this->botService->destroy($blog, $bot);

        return new WebResponse($response, route('bots.index'));
    }

    public function uploadAttribute(Request $request, int $id)
    {
        $attribute = strval($request->input('attribute'));

        $blog = $this->blogService->findOrFailActiveBlog();
        $bot = $this->botService->findOrFailActiveBlogBot($id);

        $response = $this->botService->uploadAttribute($blog, $bot, $attribute);

        return new WebResponse($response, route('bots.index'));
    }
}
