<?php

namespace App\Http\Resources\v4;

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
            'post_id' => $this->id,
            'post_type' => 'category',
            'post_title' => $this->category_name ,
            'post_image' => $this->category_image ?  asset('storage/sites/'.$this->site_id.'/categories/'.$this->category_image) : asset('storage/default.png') ,


        ];

    }
}
