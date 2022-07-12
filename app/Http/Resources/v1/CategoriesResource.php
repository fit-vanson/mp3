<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoriesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)   {
        return [
            'category_id' => $this->id,
            'category_name' => $this->category_name,
            'total_wallpaper'=>$this->wallpaper_count,
            'category_image' => $this->pivot->site_image ? $this->pivot->site_image : $this->category_image,
        ];
    }
}
