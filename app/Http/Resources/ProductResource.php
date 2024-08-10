<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'code' => $this->code,
            'images' => $this->whenLoaded('images', fn () => GalleryResource::collection($this->images)),
            'product_tags' => $this->whenLoaded('productTags', fn () => collect($this->productTags)->pluck('tag_name')),
            'product_properties' => $this->whenLoaded('productProperties', fn () => new ProductPropertyCollection($this->productProperties)),
        ];
    }
}
