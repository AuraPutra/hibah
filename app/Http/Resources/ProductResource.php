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
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'discount' => $this->discount,
            'description' => $this->description,
            'usage' => $this->usage,
            'stock' => $this->stock,
            'min_order' => $this->minOrder,
            'category' => $this->category,
            'image_path'=> $this->image_path,
        ];
    }
}
