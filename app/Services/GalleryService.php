<?php

namespace App\Services;

use App\DTO\GalleryDTO;
use App\Enums\Gallery\GalleryCategory;
use App\Facades\ResponseBuilder;
use App\Models\Blog;
use App\Models\Gallery;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class GalleryService
{
    public function getLatestQuery(Blog $blog, string $galleryType, string $galleryId, string $galleryCategory): HasMany
    {
        return $blog->galleries()
            //
            ->where('gallery_type', $galleryType)
            ->where('gallery_id', $galleryId)
            ->where('gallery_category', $galleryCategory)
            //
            ->orderDefault();
    }

    public function findOrFailActiveBlogGallery($name)
    {
        $blog = resolve(BlogService::class)->findOrFailActiveBlog();
        $gallery = $blog->galleries()->where('name', $name)->first();
        abort_unless($gallery, 404);

        return $gallery;
    }

    public function destroy(Blog $blog, Gallery $gallery)
    {
        $path = static::getGalleryPath($gallery->name);

        if (
            $gallery->delete() and
            Storage::delete($path)
        ) {
            $this->resetSelected($blog, $gallery);

            return ResponseBuilder::status(200);
        }

        return ResponseBuilder::status(500);
    }

    public function update(Blog $blog, Gallery $gallery, GalleryDTO $galleryDTO)
    {
        $validation = $galleryDTO->validate(false);
        if ($validation->errors()->isNotEmpty()) {
            return ResponseBuilder::status(402)->errors($validation->errors()->toArray());
        }

        $isSelected = ($galleryDTO->is_selected ? now()->format('Y-m-d H:i:s.u') : null);

        $gallery->gallery_order = $galleryDTO->gallery_order;
        $gallery->selected_at = $isSelected;
        if (! $gallery->save()) {
            return ResponseBuilder::status(500);
        }

        $this->resetSelected($blog, $gallery);

        return ResponseBuilder::status(200);
    }

    public function store(Blog $blog, string $galleryType, string $galleryId, GalleryCategory $galleryCategory, GalleryDTO $galleryDTO)
    {
        $validation = $galleryDTO->validate();
        if ($validation->errors()->isNotEmpty()) {
            return ResponseBuilder::status(402)->errors($validation->errors()->toArray());
        }

        $ext = $galleryDTO->file->extension();
        do {
            $name = substr(uniqid(rand(), true), 0, 12).'.'.$ext;
        } while (Gallery::query()->where('name', $name)->first());
        $isSelected = ($galleryDTO->is_selected ? now()->format('Y-m-d H:i:s.u') : null);

        $gallery = $blog->galleries()->make();
        $gallery->gallery_order = $galleryDTO->gallery_order;
        $gallery->selected_at = $isSelected;
        $gallery->ext = $ext;
        $gallery->name = $name;
        $gallery->gallery_category = $galleryCategory;
        $gallery->gallery_type = $galleryType;
        $gallery->gallery_id = $galleryId;
        if (! $gallery->save()) {
            return ResponseBuilder::status(500);
        }

        $manager = new ImageManager(new Driver());
        $image = $manager->read($galleryDTO->file->getRealPath());

        $path = static::getGalleryPath($gallery->name);

        $isUploaded = Storage::put($path, $image->encode());
        if (! $isUploaded) {
            return ResponseBuilder::status(500);
        }

        $this->resetSelected($blog, $gallery);

        return ResponseBuilder::status(200);
    }

    private function resetSelected(Blog $blog, Gallery $gallery)
    {
        $shouldSelect = $this->getLatestQuery(
            $blog,
            $gallery->gallery_type,
            $gallery->gallery_id,
            $gallery->gallery_category->value
        )->first();

        if (! $shouldSelect) {
            return;
        }

        if (empty($shouldSelect->selected_at)) {
            $shouldSelect->selected_at = now()->format('Y-m-d H:i:s.u');
            $shouldSelect->save();
        }

        $shouldNotSelects = $this->getLatestQuery(
            $blog,
            $gallery->gallery_type,
            $gallery->gallery_id,
            $gallery->gallery_category->value
        )->whereNotNull('selected_at')->where('name', '<>', $shouldSelect->name)->get();

        foreach ($shouldNotSelects as $shouldNotSelect) {
            $shouldNotSelect->selected_at = null;
            $shouldNotSelect->save();
        }
    }

    public function getGalleryUrl(Gallery $gallery)
    {
        $path = static::getGalleryPath($gallery->name);

        return Storage::url($path);
    }

    private function getGalleryPath($fileName)
    {
        return implode('/', [
            'gallery',
            $fileName,
        ]);
    }
}
