<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => [
                'raw' => $this->price,
                'formatted' => 'Rp. ' . number_format($this->price, 0, ',', '.')
            ],
            'stock' => $this->stock,
            'category' => [
                'id' => $this->category->id,
                'name' => $this->category->name
            ],
            'image' => $this->image_url
        ];
    }
}
