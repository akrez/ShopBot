<?php

namespace App\Services;

use App\DTO\GalleryDTO;
use App\Enums\Gallery\GalleryCategory;
use App\Facades\ResponseBuilder;
use App\Models\Blog;
use App\Models\Gallery;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

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

    public function create(Blog $blog, string $galleryType, string $galleryId, GalleryCategory $galleryCategory, GalleryDTO $galleryDTO)
    {
        $validation = $galleryDTO->validate();
        if ($validation->errors()->isNotEmpty()) {
            return ResponseBuilder::status(402)->errors($validation->errors()->toArray());
        }

        $ext = $galleryDTO->file->extension();
        $name = $this->generateImageFileName($ext);
        $isSelected = ($galleryDTO->is_selected ? now()->format('Y-m-d H:i:s.u') : null);

        $gallery = $blog->galleries()->make();
        $gallery->gallery_order = $galleryDTO->gallery_order;
        $gallery->selected_at = $isSelected;
        $gallery->ext = $ext;
        $gallery->name = $name;
        $gallery->gallery_category = $galleryCategory;
        $gallery->gallery_type = $galleryType;
        $gallery->gallery_id = $galleryId;
        if (!$gallery->save()) {
            return ResponseBuilder::status(500);
        }

        $isUploaded = $this->upload(
            $galleryDTO->file->getRealPath(),
            static::getBaseUri($gallery->name)
        );

        if (!$isUploaded) {
            return ResponseBuilder::status(500);
        }

        // static::resetSelected($gallery);

        return ResponseBuilder::status(200);
    }

    private function upload(string $readFilePath, string $writeFilePath): bool
    {
        $manager = new ImageManager(new Driver());
        $image = $manager->read($readFilePath);
        return Storage::put($writeFilePath, $image->encode());
    }

    public static function generateImageFileName($ext)
    {
        do {
            $name = substr(uniqid(rand(), true), 0, 12) . '.' . $ext;
        } while (Gallery::query()->where('name', $name)->first());

        return $name;
    }

    public static function getBaseUri($name)
    {
        return implode('/', [
            $name,
        ]);
    }
}
