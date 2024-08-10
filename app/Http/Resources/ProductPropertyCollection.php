<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductPropertyCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<int|string, mixed>
     */
    public function toArray($request)
    {
        return $this->collection
            ->groupBy('property_key')
            ->map(fn ($items) => [
                'property_key' => $items->first()->property_key,
                'property_values' => $items->pluck('property_value')->toArray(),
            ])
            ->values()
            ->toArray();
    }
}
