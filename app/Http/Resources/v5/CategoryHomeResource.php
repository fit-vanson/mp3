<?php

namespace App\Http\Resources\v5;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryHomeResource extends JsonResource
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
            'category_banner_text' => $this->category_name ,
            'category_banner' => $this->category_image ?  asset('storage/sites/'.$this->site_id.'/categories/'.$this->category_image) : asset('storage/default.png') ,
            'music_count' => count($this->music),
        ];
    }
}
