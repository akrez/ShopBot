<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'logo' => $this->whenLoaded('logo', fn () => new GalleryResource($this->logo)),
            'products' => $this->whenLoaded('products', fn () => ProductResource::collection($this->products)),
            'contacts' => $this->whenLoaded('contacts', fn () => ContactResource::collection($this->contacts)),
        ];
    }
}
