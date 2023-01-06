<?php

namespace App\Http\Resources\v5;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'category_id' => $this->id,
            'category_name' => $this->category_name ,
            'category_icon' => $this->category_image ?  asset('storage/sites/'.$this->site_id.'/categories/'.$this->category_image) : asset('storage/default.png') ,
            'music_count' => count($this->music),
        ];
    }
}
