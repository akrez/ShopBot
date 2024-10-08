<?php

namespace App\Services;

use App\Contracts\PortContract;
use App\DTO\ColorDTO;
use App\Models\Blog;
use App\Models\Color;
use App\Support\ResponseBuilder;

class ColorService implements PortContract
{
    public function getLatestBlogColorsQuery(Blog $blog)
    {
        return $blog->colors()->orderDefault();
    }

    public function findOrFailActiveBlogColor($colorId)
    {
        $blog = resolve(BlogService::class)->findOrFailActiveBlog();
        $color = $blog->colors()->where('id', $colorId)->first();
        abort_unless($color, 404);

        return $color;
    }

    public function getLatestBlogColorsIdNameArray(Blog $blog)
    {
        return $this
            ->getLatestBlogColorsQuery($blog)
            ->get()
            ->pluck('name', 'id')
            ->toArray();
    }

    public function firstColorById(Blog $blog, $id): ?Color
    {
        if (strlen($id)) {
            return $blog->colors()->where('id', $id)->first();
        }

        return null;
    }

    public function store(Blog $blog, ColorDTO $colorDTO)
    {
        $responseBuilder = resolve(ResponseBuilder::class)->input($colorDTO);

        $validation = $colorDTO->validate(true, ['blog' => $blog]);

        if ($validation->errors()->isNotEmpty()) {
            return $responseBuilder->status(422)->message('Unprocessable Entity')->errors($validation->errors());
        }

        $color = $blog->colors()->create($colorDTO->data());

        if (! $color) {
            return $responseBuilder->status(500)->message('Internal Server Error');
        }

        return $responseBuilder->status(201)->data($color)->message(__(':name is created successfully', [
            'name' => __('Color'),
        ]));
    }

    public function update(Blog $blog, Color $color, ColorDTO $colorDTO)
    {
        $responseBuilder = resolve(ResponseBuilder::class)->input($colorDTO);

        $validation = $colorDTO->validate(false, ['blog' => $blog, 'id' => $color->id]);

        if ($validation->errors()->isNotEmpty()) {
            return $responseBuilder->status(422)->message('Unprocessable Entity')->errors($validation->errors());
        }

        $isSuccessful = $color->update($colorDTO->data());
        if (! $isSuccessful) {
            return $responseBuilder->status(500)->message('Internal Server Error');
        }

        return $responseBuilder->data($color)->status(200)->message(__(':name is updated successfully', [
            'name' => __('Color'),
        ]));
    }

    public function destroy(Blog $blog, Color $color)
    {
        if (! $color->delete()) {
            return ResponseBuilder::new(500)->message('Internal Server Error');
        }

        return ResponseBuilder::new(200)->message(__(':name is deleted successfully', [
            'name' => __('Color'),
        ]));
    }

    public function exportToExcel(Blog $blog)
    {
        $source = [];

        $source[] = [
            __('validation.attributes.id'),
            __('validation.attributes.code'),
            __('validation.attributes.name'),
        ];

        foreach ($this->getLatestBlogColorsQuery($blog)->get() as $color) {
            $source[] = [
                $color->id,
                $color->code,
                $color->name,
            ];
        }

        return $source;
    }

    /**
     * @return array<int, ResponseBuilder>
     */
    public function importFromExcel(Blog $blog, array $rows): array
    {
        $result = [];
        //
        $skipedRow = 0;
        foreach ($rows as $row) {
            if ($skipedRow < 1) {
                $skipedRow++;

                continue;
            }
            //
            $row = ((array) $row) + array_fill(0, 3, null);
            $id = $row[0];
            //
            $colorDTO = new ColorDTO(
                $row[1],
                $row[2]
            );
            //
            $color = $this->firstColorById($blog, $id);
            //
            if ($color) {
                $result[] = $this->update($blog, $color, $colorDTO);
            } else {
                $result[] = $this->store($blog, $colorDTO);
            }
        }

        return $result;
    }
}
