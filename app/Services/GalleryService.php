<?php

namespace App\Services;

use App\DTO\GalleryDTO;
use App\Enums\Gallery\GalleryCategory;
use App\Models\Blog;
use App\Models\Gallery;
use App\Support\ResponseBuilder;
use Exception;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\AutoEncoder;
use Intervention\Image\ImageManager;

class GalleryService
{
    const MODE_CONTAIN = 'contain';

    const VALID_MODES = [self::MODE_CONTAIN];

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

    public function getGalleryUrlByModel(Gallery $gallery)
    {
        $path = static::getGalleryPathByModel($gallery);

        return Storage::url($path);
    }

    public function store(Blog $blog, string $galleryType, string $galleryId, GalleryCategory $galleryCategory, GalleryDTO $galleryDTO)
    {
        $responseBuilder = resolve(ResponseBuilder::class)->input($galleryDTO);

        $validation = $galleryDTO->validate();
        if ($validation->errors()->isNotEmpty()) {
            return $responseBuilder->status(422)->message('Unprocessable Entity')->errors($validation->errors());
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
            return $responseBuilder->status(500)->message('Internal Server Error');
        }

        $uploadResponse = $this->upload(
            $galleryDTO->file->getRealPath(),
            $this->getGalleryPathByModel($gallery)
        );
        if (! $uploadResponse->isSuccessful()) {
            return $responseBuilder
                ->status($uploadResponse->getStatus())
                ->message($uploadResponse->getMessage());
        }

        $this->resetSelected($blog, $gallery);

        return $responseBuilder->status(201)->data($gallery)->message(__(':name is created successfully', [
            'name' => $gallery->gallery_category->trans(),
        ]));
    }

    public function upload(string $sourceFilePath, string $path)
    {
        try {
            if (! file_exists($sourceFilePath)) {
                return ResponseBuilder::new(404);
            }
            //
            $manager = new ImageManager(Driver::class);
            $image = $manager->read($sourceFilePath);
            //
            $isUploaded = Storage::put($path, $image->encode(new AutoEncoder(quality: AutoEncoder::DEFAULT_QUALITY)));
            if ($isUploaded) {
                $pathinfo = pathinfo($path);

                return ResponseBuilder::new(201)->data([
                    'width' => $image->width(),
                    'height' => $image->height(),
                    'name' => $pathinfo['basename'],
                    'path' => $path,
                ]);
            }
        } catch (Exception $e) {
        }

        return ResponseBuilder::new(500);
    }

    public function update(Blog $blog, Gallery $gallery, GalleryDTO $galleryDTO)
    {
        $responseBuilder = resolve(ResponseBuilder::class)->input($galleryDTO);

        $validation = $galleryDTO->validate(false);
        if ($validation->errors()->isNotEmpty()) {
            return $responseBuilder->status(422)->message('Unprocessable Entity')->errors($validation->errors());
        }

        $isSelected = ($galleryDTO->is_selected ? now()->format('Y-m-d H:i:s.u') : null);

        $gallery->gallery_order = $galleryDTO->gallery_order;
        $gallery->selected_at = $isSelected;
        if (! $gallery->save()) {
            return $responseBuilder->status(500)->message('Internal Server Error');
        }

        $this->resetSelected($blog, $gallery);

        return $responseBuilder->status(200)->data($gallery)->message(__(':name is updated successfully', [
            'name' => $gallery->gallery_category->trans(),
        ]));
    }

    public function destroy(Blog $blog, Gallery $gallery)
    {
        $path = static::getGalleryPathByModel($gallery);

        if (
            $gallery->delete() and
            Storage::delete($path)
        ) {
            $this->resetSelected($blog, $gallery);

            return ResponseBuilder::new(200)->message(__(':name is deleted successfully', [
                'name' => $gallery->gallery_category->trans(),
            ]));
        }

        return ResponseBuilder::new(500)->message('Internal Server Error');
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

    private function getGalleryPathByModel(Gallery $gallery)
    {
        return $this->getGalleryPath($gallery->gallery_category->value, $gallery->name);
    }

    public function getGalleryPath($category, $name)
    {
        return implode('/', [
            'gallery',
            $category,
            $name,
        ]);
    }
}
