<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'package_status' => $this->package_status,
            'price' => $this->price,
            'color' => ($this->color ? new ColorResource($this->color) : null),
            'guaranty' => $this->guaranty,
            'description' => $this->description,
        ];
    }
}