<?php

namespace App\Http\Controllers;

use App\Services\GalleryService;

class GalleryController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function paint($category, $whmq, $name)
    {
        $galleryService = resolve(GalleryService::class);

        $gallery = $galleryService->firstApiGallery($category, $name);
        abort_unless($gallery, 404);

        $response = $galleryService->paint($gallery, $whmq);
        if (! $response->isSuccessful()) {
            return $response;
        }

        return Response()->download(
            $galleryService->getPath($response->getData()['path']),
            $response->getData()['name'],
            [],
            'inline'
        );

    }
}
