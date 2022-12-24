<?php

namespace App\Http\Resources\v4;

use Illuminate\Http\Resources\Json\JsonResource;

class MusicForCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        foreach ($this->music->random(1) as $item){

            return [
                'slider_id' => $item->id,
                'slider_title' => $item->title ,
                'slider_info' => $item->slider_info ,
                'songs_ids' => $item->id ,
                'slider_image' => $item->music_thumbnail_link ,
            ];
        }
    }
}
