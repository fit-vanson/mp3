<?php

namespace App\Http\Resources\v2;

use Illuminate\Http\Resources\Json\JsonResource;
use Rolandstarke\Thumbnail\Facades\Thumbnail;

class CategoriesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
            'cid' => $this->id,
            'category_name' => $this->category_name,
            'category_image' => $this->pivot->site_image ? asset('storage/categories/'.$this->pivot->site_image) : asset('storage/categories/'.$this->category_image),
            'category_image_thumb' => $this->pivot->site_image ? asset('storage/categories/'.$this->pivot->site_image) : asset('storage/categories/'.$this->category_image),
            'category_total_wall' => $this->wallpaper_count,
        ];
    }
}
