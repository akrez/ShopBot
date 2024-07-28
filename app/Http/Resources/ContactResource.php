<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'contact_type' => $this->contact_type,
            'contact_key' => $this->contact_key,
            'contact_value' => $this->contact_value,
            'contact_link' => $this->contact_link,
            'contact_order' => $this->contact_order,
        ];
    }
}
